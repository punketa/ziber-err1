<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Panel de administración central (administrazioa.php)
     */
    public function dashboard()
    {
        $stats = [
            'total_usuarios' => User::count(),
            'total_alumnos' => User::where('role', 'ikasle')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_cursos' => Curso::count(),
            'cursos_irekita' => Curso::where('egoera', 'irekita')->count(),
            'total_matriculas' => Matricula::count(),
            'matriculas_activas' => Matricula::where('egoera', 'onartua')->count(),
        ];

        $azken_matrikulak = Matricula::with(['usuario', 'curso'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $azken_ikastaroak = Curso::withCount(['matriculas' => function ($q) {
                $q->where('egoera', '!=', 'baja');
            }])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('administrazioa', compact('stats', 'azken_matrikulak', 'azken_ikastaroak'));
    }
}
