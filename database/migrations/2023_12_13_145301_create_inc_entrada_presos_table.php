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
        Schema::create('inc_entrada_preso', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('matricula')->nullable();


            $table->unsignedBigInteger('pessoa_vinculada_id');
            $table->foreign('pessoa_vinculada_id')->references('id')->on('pessoas');

            $table->unsignedBigInteger('entrada_id');
            $table->foreign('entrada_id')->references('id')->on('inc_entrada');

            $table->string('pai')->nullable();
            $table->string('mae')->nullable();
            $table->string('rg')->nullable();
            $table->string('cpf')->nullable();

            $table->unsignedBigInteger('tipo_mov_id');
            $table->foreign('tipo_mov_id')->references('id')->on('ref_tipo_movimentacao');

            $table->unsignedBigInteger('motivo_mov_id');
            $table->foreign('motivo_mov_id')->references('id')->on('ref_motivo_movimentacao');

            $table->date('data_prisao')->nullable();
            $table->text('informacoes')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('seguro_bln')->default(false);
            $table->boolean('lancado_cimic_bln')->default(false);
            $table->boolean('lancado_cimic_bln')->default(false);

            $table->unsignedBigInteger('id_user_created');
            $table->foreign('id_user_created')->references('id')->on('users');
            $table->string('ip_created')->nullable();

            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->foreign('id_user_updated')->references('id')->on('users');
            $table->string('ip_updated')->nullable();

            $table->unsignedBigInteger('id_user_deleted')->nullable();
            $table->foreign('id_user_deleted')->references('id')->on('users');
            $table->string('ip_deleted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inc_entrada_preso');
    }
};
