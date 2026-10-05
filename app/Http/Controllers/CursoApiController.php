<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoApiController extends Controller
{
    /**
     * Lista de cursos en formato JSON
     */
    public function index()
    {
        $cursos = Curso::where('egoera', 'irekita')->get();
        return response()->json([
            'success' => true,
            'data' => $cursos,
        ], 200);
    }

    /**
     * Detalle de curso por ID
     */
    public function show($id)
    {
        $curso = Curso::find($id);

        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Ikastaroa ez da aurkitu (Curso no encontrado)'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $curso,
        ], 200);
    }
}
