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
        Schema::create('inc_entrada_presos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrada_id');
            $table->foreign('entrada_id')->references('id')->on('inc_entradas');

            $table->string('nome');
            $table->string('matricula')->nullable();
            $table->string('rg')->nullable();
            $table->string('cpf')->nullable();
            $table->string('mae')->nullable();
            $table->string('pai')->nullable();
            $table->date('data_prisao')->nullable();
            $table->text('informacoes')->nullable();
            $table->text('observacoes')->nullable();

            $table->unsignedBigInteger('preso_id');
            $table->foreign('preso_id')->references('id')->on('presos');

            $table->unsignedBigInteger('movimentacao_id');
            $table->foreign('movimentacao_id')->references('id')->on('ref_movimentacao_preso');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('ref_status');

            $table->unsignedBigInteger('id_user_created');
            $table->foreign('id_user_created')->references('id')->on('users');
            $table->string('ip_created')->nullable();
            $table->timestamp('created_at');

            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->foreign('id_user_updated')->references('id')->on('users');
            $table->string('ip_updated')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('id_user_deleted')->nullable();
            $table->foreign('id_user_deleted')->references('id')->on('users');
            $table->string('ip_deleted')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inc_entrada_presos');
    }
};
