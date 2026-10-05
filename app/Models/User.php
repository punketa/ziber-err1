<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Erabiltzaileen datu-eredua (Model).
 * Autentifikazioa, rolak (admin / ikasle) eta erabiltzailearen erlazioak kudeatzen ditu.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Datu-baseko taularen izena.
     */
    protected $table = 'usuarios';

    /**
     * Masiboki esleitu daitezkeen eremuak.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Serializazioan ezkutatu behar diren eremuak.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Moten bihurketa (casting).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Erabiltzaile honen matrikulak lortzen ditu (1:N erlazioa).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'usuario_id');
    }

    /**
     * Erabiltzaileak inskribatuta dituen ikastaroak (N:M erlazioa).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'matriculas', 'usuario_id', 'curso_id')
            ->withPivot(['id', 'data', 'egoera', 'kalifikazioa'])
            ->withTimestamps();
    }

    /**
     * Erabiltzailea administratzailea den egiaztatzen du.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Erabiltzailea ikaslea den egiaztatzen du.
     *
     * @return bool
     */
    public function isIkasle(): bool
    {
        return $this->role === 'ikasle';
    }
}
