<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = [
        'kodea',
        'izena',
        'deskribapena',
        'iraupena_orduak',
        'plazak',
        'prezioa',
        'hasiera_data',
        'bukaera_data',
        'egoera',
    ];

    protected $casts = [
        'hasiera_data' => 'date',
        'bukaera_data' => 'date',
        'prezioa' => 'decimal:2',
        'iraupena_orduak' => 'integer',
        'plazak' => 'integer',
    ];

    /**
     * Relación con las matrículas del curso
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'curso_id');
    }

    /**
     * Relación con los usuarios inscritos
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'matriculas', 'curso_id', 'usuario_id')
            ->withPivot(['id', 'data', 'egoera', 'kalifikazioa'])
            ->withTimestamps();
    }

    /**
     * Calcula las plazas disponibles restantes
     */
    public function plazasDisponibles(): int
    {
        $ocupadas = $this->matriculas()->where('egoera', '!=', 'baja')->count();
        return max(0, $this->plazak - $ocupadas);
    }

    /**
     * Verifica si el curso admite nuevas matrículas
     */
    public function isIrekita(): bool
    {
        return $this->egoera === 'irekita' && $this->plazasDisponibles() > 0;
    }
}
