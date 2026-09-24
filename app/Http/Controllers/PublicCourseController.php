<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Ikasle eta bisitarientzako ikastaroen kontrolatzaile publikoa.
 * Katalogoa, xehetasunak, matrikulazio prozesua eta ikaslearen panel propioa kudeatzen ditu.
 */
class PublicCourseController extends Controller
{
    /**
     * Hasierako orria (/): Ikastaro guztien eskaintza, plazak eta egoera bistaratzen ditu (bilaketa-iragazkiarekin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
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
     * Ikastaro zehatz baten xehetasunak eta matrikulazio aukera bistaratzen ditu.
     *
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\View\View
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
     * Ikasle baten matrikulazioa prozesatzen du ikastaro batean:
     * - Ikastaroaren egoera irekita dagoela eta plaza libreak daudela egiaztatzen du.
     * - Matrikulazio bikoiztuak prebenitzen ditu (Datuen osotasuna eta segurtasuna).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\Http\RedirectResponse
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

        // Aurretik matrikulatuta dagoen egiaztatu
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
     * Ikaslearen panel pertsonala ('Nire Matrikulak'): ikaslearen ikastaroak, egoerak eta notak.
     *
     * @return \Illuminate\View\View
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
     * Matrikula propioa bertan behera uztea (IDOR arriskua ekiditeko baimen-kontrola).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelEnrollment(Request $request, Matricula $matricula)
    {
        $user = Auth::user();

        // Baimen kontrola (Insecure Direct Object Reference - IDOR saihesteko)
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
