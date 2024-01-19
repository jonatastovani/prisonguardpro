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

            $table->unsignedBigInteger('preso_id')->nullable();
            $table->foreign('preso_id')->references('id')->on('presos');

            $table->unsignedBigInteger('movimentacao_id')->nullable();
            $table->foreign('movimentacao_id')->references('id')->on('ref_movimentacao_preso');

            $table->unsignedBigInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('ref_status');

            $table->unsignedBigInteger('created_user_id');
            $table->foreign('created_user_id')->references('id')->on('users');
            $table->string('created_ip')->nullable();
            $table->timestamp('created_at');

            $table->unsignedBigInteger('updated_user_id')->nullable();
            $table->foreign('updated_user_id')->references('id')->on('users');
            $table->string('updated_ip')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_user_id')->nullable();
            $table->foreign('deleted_user_id')->references('id')->on('users');
            $table->string('deleted_ip')->nullable();
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
