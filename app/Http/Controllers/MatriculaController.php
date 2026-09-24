<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Matrikulen kudeaketarako kontrolatzailea (CRUD osoa).
 * Administratzaileari ikasleen matrikulak kudeatu, egoerak aldatu eta kalifikazioak jartzeko aukera ematen dio.
 */
class MatriculaController extends Controller
{
    /**
     * Matrikula guztien zerrenda bistaratzen du, ikasle eta ikastaroen datuekin (Eager Loading).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $matriculas = Matricula::with(['usuario', 'curso'])->get();
        return view('admin.matriculas.index', compact('matriculas'));
    }

    /**
     * Matrikula berria sortzeko formularioa bistaratzen du ikasle eta ikastaroen zerrendarekin.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $usuarios = User::where('role', 'ikasle')->get();
        $cursos = Curso::all();
        return view('admin.matriculas.create', compact('usuarios', 'cursos'));
    }

    /**
     * Matrikula berriaren datuak balidatu eta datu-basean gordetzen ditu.
     * Segurtasuna: Ikasle bera ikastaro berean birritan matrikulatzea saihesten du.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'curso_id' => 'required|exists:cursos,id',
            'data' => 'required|date',
            'egoera' => 'required|in:onartua,pendiente,baja',
            'kalifikazioa' => 'nullable|numeric|between:0,10',
        ]);

        $exists = Matricula::where('usuario_id', $validated['usuario_id'])
            ->where('curso_id', $validated['curso_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Ikaslea dagoeneko ikastaro honetan matrikulatuta dago.')->withInput();
        }

        $matricula = Matricula::create($validated);

        Log::info("Admin-ak matrikula sortu du: User {$matricula->usuario_id}, Curso {$matricula->curso_id}");

        return redirect()->route('admin.matriculas.index')->with('success', 'Matrikula arrakastaz sortu da!');
    }

    /**
     * Matrikula aldatzeko formularioa erakusten du (egoera edo kalifikazioa editatzeko).
     *
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\View\View
     */
    public function edit(Matricula $matricula)
    {
        $usuarios = User::where('role', 'ikasle')->get();
        $cursos = Curso::all();
        return view('admin.matriculas.edit', compact('matricula', 'usuarios', 'cursos'));
    }

    /**
     * Aldatutako matrikularen datuak balidatu eta datu-basean eguneratzen ditu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Matricula $matricula)
    {
        $validated = $request->validate([
            'data' => 'required|date',
            'egoera' => 'required|in:onartua,pendiente,baja',
            'kalifikazioa' => 'nullable|numeric|between:0,10',
        ]);

        $matricula->update($validated);

        Log::info("Matrikula eguneratu da: ID {$matricula->id}, Egoera {$matricula->egoera}");

        return redirect()->route('admin.matriculas.index')->with('success', 'Matrikula arrakastaz eguneratu da!');
    }

    /**
     * Matrikula bat datu-basetik ezabatzen du.
     *
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Matricula $matricula)
    {
        $id = $matricula->id;
        $matricula->delete();

        Log::info("Matrikula ezabatu da: ID {$id}");

        return redirect()->route('admin.matriculas.index')->with('success', 'Matrikula ezabatu da!');
    }
}
