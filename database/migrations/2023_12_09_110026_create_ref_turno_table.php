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
        Schema::create('ref_turnos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->boolean('periodo_diurno_bln'); // True para caso o turno for diurno
            $table->boolean('plantao_bln'); // True para caso o turno for plantão
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_turnos');
    }
};
