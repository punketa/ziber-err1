<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Solo crea el usuario administrador inicial para arrancar la plataforma.
     * Cero cursos ni matrículas de prueba para mantener la base de datos limpia.
     */
    public function run(): void
    {
        $adminEmail = env('INITIAL_ADMIN_EMAIL', 'admin@ciber.eus');
        $adminPassword = env('INITIAL_ADMIN_PASSWORD', 'admin1234');

        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Administratzailea',
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ]
        );
    }
}
