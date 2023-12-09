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

        Schema::create('ref_turno_permissao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turno_id')->nullable();
            $table->foreign('turno_id')->references('id')->on('ref_turnos');
            $table->unsignedBigInteger('tipo_permissao_id')->nullable();
            $table->foreign('tipo_permissao_id')->references('id')->on('ref_turno_tipo_permissao');
            $table->unsignedBigInteger('permissao_id')->nullable();
            $table->foreign('permissao_id')->references('id')->on('ref_permissao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_turno_permissao');
    }
};
