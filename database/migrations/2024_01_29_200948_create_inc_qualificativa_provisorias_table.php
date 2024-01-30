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
        Schema::create('inc_qualificativa_provisorias', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('passagem_id');
            $table->foreign('passagem_id')->references('id')->on('inc_entrada_presos');

            $table->string('matricula')->nullable();
            $table->string('nome');
            $table->string('nome_social')->nullable();
            $table->string('mae')->nullable();
            $table->string('pai')->nullable();
            $table->date('data_nasc')->nullable();

            $table->unsignedBigInteger('cidade_nasc_id')->nullable();
            $table->foreign('cidade_nasc_id')->references('id')->on('ref_cidades');

            $table->unsignedBigInteger('genero_id')->nullable();
            $table->foreign('genero_id')->references('id')->on('ref_generos');

            $table->unsignedBigInteger('escolaridade_id')->nullable();
            $table->foreign('escolaridade_id')->references('id')->on('ref_escolaridades');

            $table->unsignedBigInteger('estado_civil_id')->nullable();
            $table->foreign('estado_civil_id')->references('id')->on('ref_estado_civil');

            $table->double('estatura', 3, 2)->nullable();
            $table->double('peso', 4, 1)->nullable();

            $table->unsignedBigInteger('cutis_id')->nullable();
            $table->foreign('cutis_id')->references('id')->on('ref_cutis');

            $table->unsignedBigInteger('cabelo_tipo_id')->nullable();
            $table->foreign('cabelo_tipo_id')->references('id')->on('ref_cabelo_tipos');

            $table->unsignedBigInteger('cabelo_cor_id')->nullable();
            $table->foreign('cabelo_cor_id')->references('id')->on('ref_cabelo_cor');

            $table->unsignedBigInteger('olho_cor_id')->nullable();
            $table->foreign('olho_cor_id')->references('id')->on('ref_olho_cor');

            $table->unsignedBigInteger('olho_tipo_id')->nullable();
            $table->foreign('olho_tipo_id')->references('id')->on('ref_olho_tipos');

            $table->unsignedBigInteger('crenca_id')->nullable();
            $table->foreign('crenca_id')->references('id')->on('ref_crencas');

            $table->text('sinais')->nullable();

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
        Schema::dropIfExists('inc_qualificativa_provisorias');
    }
};
