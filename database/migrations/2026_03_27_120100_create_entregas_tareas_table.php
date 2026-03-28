<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entregas_tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('users');
            $table->string('archivo_path');
            $table->string('archivo_nombre_original');
            $table->string('archivo_mime');
            $table->unsignedBigInteger('archivo_size');
            $table->timestamp('entregado_en');
            $table->timestamps();

            $table->unique(['tarea_id', 'alumno_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas_tareas');
    }
};
