<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Prueba 1: Acceso a la página principal y rutas requeridas (index.php)
     */
    public function test_public_can_access_index_and_index_php(): void
    {
        $response1 = $this->get('/');
        $response1->assertStatus(200);
        $response1->assertSee('Ikastaroen Eskaintza');

        $response2 = $this->get('/index.php');
        $response2->assertStatus(200);
        $response2->assertSee('Ikastaroen Eskaintza');
    }

    /**
     * Prueba 2: Cabeceras de seguridad HTTP (OWASP Top 10 A05)
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }

    /**
     * Prueba 3: Invitados bloqueados de administrazioa.php
     */
    public function test_guest_is_redirected_from_administrazioa(): void
    {
        $response = $this->get('/administrazioa');
        $response->assertRedirect('/login');

        $responsePhp = $this->get('/administrazioa.php');
        $responsePhp->assertRedirect('/login');
    }

    /**
     * Prueba 4: RBAC - Alumno autenticado recibe 403 Forbidden en administrazioa
     */
    public function test_student_cannot_access_administrazioa(): void
    {
        $student = User::create([
            'name' => 'Ikasle Test',
            'email' => 'ikasle.test@uni.eus',
            'password' => 'secret123',
            'role' => 'ikasle',
        ]);

        $response = $this->actingAs($student)->get('/administrazioa');
        $response->assertStatus(403);

        $responsePhp = $this->actingAs($student)->get('/administrazioa.php');
        $responsePhp->assertStatus(403);
    }

    /**
     * Prueba 5: RBAC - Administrador autenticado accede con éxito a administrazioa.php
     */
    public function test_admin_can_access_administrazioa(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/administrazioa');
        $response->assertStatus(200);
        $response->assertSee('Administrazio Panela');

        $responsePhp = $this->actingAs($admin)->get('/administrazioa.php');
        $responsePhp->assertStatus(200);
        $responsePhp->assertSee('Administrazio Panela');
    }

    /**
     * Prueba 6: Matrícula de estudiante en curso con plazas
     */
    public function test_student_can_enroll_in_open_course(): void
    {
        $student = User::create([
            'name' => 'Jon Test',
            'email' => 'jon.test@uni.eus',
            'password' => 'secret123',
            'role' => 'ikasle',
        ]);

        $curso = Curso::create([
            'kodea' => 'TEST-01',
            'izena' => 'Curso Test 01',
            'deskribapena' => 'Deskribapena de prueba',
            'iraupena_orduak' => 20,
            'plazak' => 10,
            'prezioa' => 0.00,
            'hasiera_data' => '2026-11-01',
            'bukaera_data' => '2026-12-01',
            'egoera' => 'irekita',
        ]);

        $response = $this->actingAs($student)->post("/ikastaroak/{$curso->id}/matrikulatu");
        $response->assertRedirect('/nire-matrikulak');

        $this->assertDatabaseHas('matriculas', [
            'usuario_id' => $student->id,
            'curso_id' => $curso->id,
            'egoera' => 'onartua',
        ]);
    }

    /**
     * Prueba 7: Ciberseguridad - Prevención de doble matrícula (integridad)
     */
    public function test_student_cannot_double_enroll_in_same_course(): void
    {
        $student = User::create([
            'name' => 'Mikel Test',
            'email' => 'mikel.test@uni.eus',
            'password' => 'secret123',
            'role' => 'ikasle',
        ]);

        $curso = Curso::create([
            'kodea' => 'TEST-02',
            'izena' => 'Curso Test 02',
            'deskribapena' => 'Deskribapena de prueba',
            'iraupena_orduak' => 20,
            'plazak' => 10,
            'prezioa' => 0.00,
            'hasiera_data' => '2026-11-01',
            'bukaera_data' => '2026-12-01',
            'egoera' => 'irekita',
        ]);

        Matricula::create([
            'usuario_id' => $student->id,
            'curso_id' => $curso->id,
            'data' => now()->toDateString(),
            'egoera' => 'onartua',
        ]);

        $response = $this->actingAs($student)->post("/ikastaroak/{$curso->id}/matrikulatu");
        $response->assertSessionHas('error');
    }

    /**
     * Prueba 8: Registro seguro de usuario con rol 'ikasle' forzado (Anti Privilege Escalation)
     */
    public function test_user_registration_enforces_ikasle_role(): void
    {
        $payload = [
            'name' => 'Test Hacker',
            'email' => 'hacker@test.eus',
            'password' => 'Password1234!',
            'password_confirmation' => 'Password1234!',
            'role' => 'admin', // Intento de escalar privilegios
        ];

        $response = $this->post('/register', $payload);
        $response->assertRedirect('/');

        // Verificar que en base de datos el rol es estrictamente 'ikasle', ignorando 'role' => 'admin'
        $this->assertDatabaseHas('usuarios', [
            'email' => 'hacker@test.eus',
            'role' => 'ikasle',
        ]);
    }

    /**
     * Prueba 9: Prevención de auto-eliminación de administrador
     */
    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->delete("/admin/usuarios/{$admin->id}");
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('usuarios', [
            'id' => $admin->id,
        ]);
    }

    /**
     * Prueba 10: Admin puede crear un curso con éxito
     */
    public function test_admin_can_create_course(): void
    {
        $admin = User::where('role', 'admin')->first();

        $courseData = [
            'kodea' => 'TEST-99',
            'izena' => 'Curso de Test Automatizado',
            'deskribapena' => 'Descripción detallada para pruebas de testing.',
            'iraupena_orduak' => 30,
            'plazak' => 15,
            'prezioa' => 0.00,
            'hasiera_data' => '2026-11-01',
            'bukaera_data' => '2026-12-01',
            'egoera' => 'irekita',
        ];

        $response = $this->actingAs($admin)->post('/admin/cursos', $courseData);
        $response->assertRedirect('/admin/cursos');

        $this->assertDatabaseHas('cursos', [
            'kodea' => 'TEST-99',
        ]);
    }

}
