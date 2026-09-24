<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tabla asociada en la base de datos MySQL
     */
    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
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
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
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
     * Relación con las matrículas del usuario
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'usuario_id');
    }

    /**
     * Relación muchos a muchos con cursos a través de matrículas
     */
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'matriculas', 'usuario_id', 'curso_id')
            ->withPivot(['id', 'data', 'egoera', 'kalifikazioa'])
            ->withTimestamps();
    }

    /**
     * Comprobar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Comprobar si el usuario es estudiante
     */
    public function isIkasle(): bool
    {
        return $this->role === 'ikasle';
    }
}
