<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicCourseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MatriculaController;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikazioen Garapena eta Web Plataforma
|--------------------------------------------------------------------------
*/

// Orri nagusia (index.php eta /): Ikastaroen eskaintza eta aurkezpena
Route::get('/', [PublicCourseController::class, 'index'])->name('home');
Route::get('/index.php', [PublicCourseController::class, 'index']);
Route::get('/ikastaroak/{curso}', [PublicCourseController::class, 'show'])->name('cursos.public.show');

// Autentikazioa (Login eta Erregistroa)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Ikasleen gunea (Matrikulazioak eta nire ikastaroak)
Route::middleware('auth')->group(function () {
    Route::post('/ikastaroak/{curso}/matrikulatu', [PublicCourseController::class, 'enroll'])->name('cursos.enroll');
    Route::get('/nire-matrikulak', [PublicCourseController::class, 'myEnrollments'])->name('student.matriculas');
    Route::delete('/nire-matrikulak/{matricula}', [PublicCourseController::class, 'cancelEnrollment'])->name('student.matriculas.cancel');
});

// Administrazio atala (administrazioa.php eta /administrazioa) - Babestua (RBAC: admin bakarrik)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/administrazioa', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/administrazioa.php', [AdminController::class, 'dashboard']);

    // CRUD Cursos
    Route::resource('/admin/cursos', CursoController::class)->names('admin.cursos');

    // CRUD Usuarios
    Route::resource('/admin/usuarios', UsuarioController::class)->names('admin.usuarios');

    // CRUD Matriculas
    Route::resource('/admin/matriculas', MatriculaController::class)->names('admin.matriculas');
});
