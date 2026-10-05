<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Matrikulen datu-eredua (Model).
 * Ikasleen eta ikastaroen arteko erlazioa, data, egoera eta kalifikazioak kudeatzen ditu.
 */
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
     * Matrikula honi dagokion erabiltzailea / ikaslea lortzen du (N:1 erlazioa).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Matrikula honi dagokion ikastaroa lortzen du (N:1 erlazioa).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}
