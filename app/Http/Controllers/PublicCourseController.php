<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PublicCourseController extends Controller
{
    /**
     * Página principal (index.php / /):
     * Muestra la oferta completa de cursos con plazas y estado.
     */
    public function index(Request $request)
    {
        $search = $request->input('q');

        $query = Curso::withCount(['matriculas' => function ($q) {
            $q->where('egoera', '!=', 'baja');
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('izena', 'like', "%{$search}%")
                  ->orWhere('kodea', 'like', "%{$search}%")
                  ->orWhere('deskribapena', 'like', "%{$search}%");
            });
        }

        $cursos = $query->orderBy('hasiera_data', 'asc')->get();

        $userMatriculasIds = [];
        if (Auth::check()) {
            $userMatriculasIds = Auth::user()->matriculas()
                ->where('egoera', '!=', 'baja')
                ->pluck('curso_id')
                ->toArray();
        }

        return view('index', compact('cursos', 'userMatriculasIds', 'search'));
    }

    /**
     * Detalle de un curso específico
     */
    public function show(Curso $curso)
    {
        $curso->loadCount(['matriculas' => function ($q) {
            $q->where('egoera', '!=', 'baja');
        }]);

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Auth::user()->matriculas()
                ->where('curso_id', $curso->id)
                ->where('egoera', '!=', 'baja')
                ->exists();
        }

        return view('cursos.show', compact('curso', 'isEnrolled'));
    }

    /**
     * Proceso de matriculación de un alumno en un curso:
     * - Comprueba estado del curso
     * - Comprueba disponibilidad de plazas
     * - Evita ataques de doble matriculación
     */
    public function enroll(Request $request, Curso $curso)
    {
        $user = Auth::user();

        if ($curso->egoera !== 'irekita') {
            return back()->with('error', 'Ikastaro honen matrikulazio epea itxita dago.');
        }

        if ($curso->plazasDisponibles() <= 0) {
            return back()->with('error', 'Ez dago plaza librerik ikastaro honetan.');
        }

        // Comprobar si ya existe registro previo
        $existing = Matricula::where('usuario_id', $user->id)
            ->where('curso_id', $curso->id)
            ->first();

        if ($existing) {
            if ($existing->egoera === 'baja') {
                $existing->update([
                    'egoera' => 'onartua',
                    'data' => now()->toDateString()
                ]);
                Log::info("Ikaslea berriro matrikulatu da: {$user->email} en {$curso->kodea}");
                return redirect()->route('student.matriculas')->with('success', 'Zure matrikulazioa berriro aktibatu da arrakastaz!');
            }

            return back()->with('error', 'Dagoeneko ikastaro honetan matrikulatuta zaude.');
        }

        Matricula::create([
            'usuario_id' => $user->id,
            'curso_id' => $curso->id,
            'data' => now()->toDateString(),
            'egoera' => 'onartua',
            'kalifikazioa' => null,
        ]);

        Log::info("Matrikulazio berria: {$user->email} en curso {$curso->kodea} ({$curso->izena})", [
            'ip' => $request->ip()
        ]);

        return redirect()->route('student.matriculas')->with('success', "Zorionak {$user->name}! Arrakastaz matrikulatu zara '{$curso->izena}' ikastaroan.");
    }

    /**
     * Panel personal del alumno (Nire Matrikulak)
     */
    public function myEnrollments()
    {
        $matriculas = Auth::user()->matriculas()
            ->with('curso')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ikasle.nire_matrikulak', compact('matriculas'));
    }

    /**
     * Cancelar matrícula propia (Protección IDOR: solo la propia matrícula del usuario)
     */
    public function cancelEnrollment(Request $request, Matricula $matricula)
    {
        $user = Auth::user();

        // Control de autorización (prevenir Insecure Direct Object Reference)
        if ($matricula->usuario_id !== $user->id && !$user->isAdmin()) {
            Log::warning('Segurtasun abisua: Baimenik gabeko matrikula kentze saiakera (IDOR attempt)', [
                'user_id' => $user->id,
                'target_matricula_id' => $matricula->id,
                'ip' => $request->ip()
            ]);
            abort(403, 'Ez duzu baimenik matrikula hau aldatzeko.');
        }

        $matricula->update(['egoera' => 'baja']);

        Log::info("Matrikula baja eman da: User {$user->email}, Matricula {$matricula->id}");

        return back()->with('success', 'Matrikula bertan behera utzi da.');
    }
}
