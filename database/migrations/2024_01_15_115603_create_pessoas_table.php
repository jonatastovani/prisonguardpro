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
        Schema::dropIfExists('pessoas');
    }
};
