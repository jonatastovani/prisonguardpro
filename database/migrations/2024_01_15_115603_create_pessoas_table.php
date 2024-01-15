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
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nome_social')->nullable();
            $table->string('mae');
            $table->string('pai');
            $table->date('data_nasc')->nullable();

            $table->unsignedBigInteger('cidade_nasc_id')->nullable();
            $table->foreign('cidade_nasc_id')->references('id')->on('ref_cidades');

            $table->unsignedBigInteger('genero_id')->nullable();
            $table->foreign('genero_id')->references('id')->on('ref_generos');

            $table->unsignedBigInteger('escolaridade_id')->nullable();
            $table->foreign('escolaridade_id')->references('id')->on('ref_escolaridades');

            $table->unsignedBigInteger('estado_civil_id')->nullable();
            $table->foreign('estado_civil_id')->references('id')->on('ref_estado_civil');

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
        Schema::dropIfExists('pessoas');
    }
};
