<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MatriculaController extends Controller
{
    public function index()
    {
        $matriculas = Matricula::with(['usuario', 'curso'])->get();
        return view('admin.matriculas.index', compact('matriculas'));
    }

    public function create()
    {
        $usuarios = User::where('role', 'ikasle')->get();
        $cursos = Curso::all();
        return view('admin.matriculas.create', compact('usuarios', 'cursos'));
    }

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

    public function edit(Matricula $matricula)
    {
        $usuarios = User::where('role', 'ikasle')->get();
        $cursos = Curso::all();
        return view('admin.matriculas.edit', compact('matricula', 'usuarios', 'cursos'));
    }

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

    public function destroy(Matricula $matricula)
    {
        $id = $matricula->id;
        $matricula->delete();

        Log::info("Matrikula ezabatu da: ID {$id}");

        return redirect()->route('admin.matriculas.index')->with('success', 'Matrikula ezabatu da!');
    }
}
