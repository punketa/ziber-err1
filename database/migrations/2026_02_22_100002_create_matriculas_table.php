<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->date('data');
            $table->enum('egoera', ['onartua', 'pendiente', 'baja'])->default('onartua');
            $table->decimal('kalifikazioa', 5, 2)->nullable();
            $table->timestamps();

            // Evitar matriculaciones duplicadas (integridad y ciberseguridad)
            $table->unique(['usuario_id', 'curso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
