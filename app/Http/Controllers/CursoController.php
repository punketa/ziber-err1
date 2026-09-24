<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::withCount(['matriculas' => function ($q) {
            $q->where('egoera', '!=', 'baja');
        }])->get();

        return view('admin.cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('admin.cursos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kodea' => 'required|string|max:50|unique:cursos,kodea',
            'izena' => 'required|string|max:255',
            'deskribapena' => 'required|string',
            'iraupena_orduak' => 'required|integer|min:1',
            'plazak' => 'required|integer|min:1',
            'prezioa' => 'required|numeric|min:0',
            'hasiera_data' => 'required|date',
            'bukaera_data' => 'required|date|after_or_equal:hasiera_data',
            'egoera' => 'required|in:irekita,itxita,amaituta',
        ]);

        $curso = Curso::create($validated);

        Log::info("Ikastaro berria sortu da: {$curso->kodea} ({$curso->izena})");

        return redirect()->route('admin.cursos.index')->with('success', 'Ikastaroa arrakastaz sortu da!');
    }

    public function show(Curso $curso)
    {
        $curso->load(['matriculas.usuario']);
        return view('admin.cursos.show', compact('curso'));
    }

    public function edit(Curso $curso)
    {
        return view('admin.cursos.edit', compact('curso'));
    }

    public function update(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'kodea' => 'required|string|max:50|unique:cursos,kodea,' . $curso->id,
            'izena' => 'required|string|max:255',
            'deskribapena' => 'required|string',
            'iraupena_orduak' => 'required|integer|min:1',
            'plazak' => 'required|integer|min:1',
            'prezioa' => 'required|numeric|min:0',
            'hasiera_data' => 'required|date',
            'bukaera_data' => 'required|date|after_or_equal:hasiera_data',
            'egoera' => 'required|in:irekita,itxita,amaituta',
        ]);

        $curso->update($validated);

        Log::info("Ikastaroa eguneratu da: {$curso->kodea}");

        return redirect()->route('admin.cursos.index')->with('success', 'Ikastaroa arrakastaz eguneratu da!');
    }

    public function destroy(Curso $curso)
    {
        $izena = $curso->izena;
        $curso->delete();

        Log::info("Ikastaroa ezabatu da: {$izena}");

        return redirect()->route('admin.cursos.index')->with('success', 'Ikastaroa ezabatu da!');
    }
}
