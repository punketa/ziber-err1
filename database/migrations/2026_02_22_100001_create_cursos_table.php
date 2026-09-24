<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('kodea', 50)->unique();
            $table->string('izena', 255);
            $table->text('deskribapena');
            $table->unsignedInteger('iraupena_orduak')->default(40);
            $table->unsignedInteger('plazak')->default(20);
            $table->decimal('prezioa', 8, 2)->default(0.00);
            $table->date('hasiera_data');
            $table->date('bukaera_data');
            $table->enum('egoera', ['irekita', 'itxita', 'amaituta'])->default('irekita');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
