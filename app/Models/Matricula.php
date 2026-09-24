<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';

    protected $fillable = [
        'usuario_id',
        'curso_id',
        'data',
        'egoera',
        'kalifikazioa',
    ];

    protected $casts = [
        'data' => 'date',
        'kalifikazioa' => 'decimal:2',
    ];

    /**
     * Relación con el usuario (estudiante)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con el curso
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}
