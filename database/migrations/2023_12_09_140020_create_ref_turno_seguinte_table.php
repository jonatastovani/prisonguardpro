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
        Schema::create('ref_turno_seguinte', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turno_id');
            $table->foreign('turno_id')->references('id')->on('ref_turnos');
            $table->unsignedBigInteger('turno_seguinte_id');
            $table->foreign('turno_seguinte_id')->references('id')->on('ref_turnos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_turno_seguinte');
    }
};
