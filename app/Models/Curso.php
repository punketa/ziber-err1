<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Ikastaroen datu-eredua (Model).
 * Plataformako ikastaroen informazioa, plazak eta egoera kudeatzen ditu.
 */
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
     * Ikastaro honi lotutako matrikula guztiak lortzen ditu (1:N erlazioa).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'curso_id');
    }

    /**
     * Ikastaro honetan matrikulatuta dauden ikasle/erabiltzaileak (N:M erlazioa).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'matriculas', 'curso_id', 'usuario_id')
            ->withPivot(['id', 'data', 'egoera', 'kalifikazioa'])
            ->withTimestamps();
    }

    /**
     * Libre geratzen diren plaza kopurua kalkulatzen du (baja egoeran ez daudenak kenduta).
     *
     * @return int
     */
    public function plazasDisponibles(): int
    {
        $ocupadas = $this->matriculas()->where('egoera', '!=', 'baja')->count();
        return max(0, $this->plazak - $ocupadas);
    }

    /**
     * Ikastaroan matrikula berririk onartzen den egiaztatzen du (irekita eta plazak libre).
     *
     * @return bool
     */
    public function isIrekita(): bool
    {
        return $this->egoera === 'irekita' && $this->plazasDisponibles() > 0;
    }
}
