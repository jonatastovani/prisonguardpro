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
        Schema::create('user_permissao_temporaria', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('permissao_id');
            $table->foreign('permissao_id')->references('id')->on('ref_permissao');

            $table->boolean('substituto')->default(false); // True para permissão que é substituto de alguma permissão que compete a diretor

            $table->date('data_inicio');
            $table->date('data_termino');

            $table->unsignedBigInteger('id_user_created');
            $table->foreign('id_user_created')->references('id')->on('users');
            $table->string('ip_created')->nullable();

            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->foreign('id_user_updated')->references('id')->on('users');
            $table->string('ip_updated')->nullable();

            $table->unsignedBigInteger('id_user_deleted')->nullable();
            $table->foreign('id_user_deleted')->references('id')->on('users');
            $table->string('ip_deleted')->nullable();
            
            // Adiciona automaticamente os campos created_at e updated_at
            // $table->timestamps();

            // Define o campo created_at como não nulo
            $table->timestamp('created_at');

            // Define o campo updated_at como nulo
            $table->timestamp('updated_at')->nullable();

            // Adiciona campo deleted_at para soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissao_temporaria');
    }
};
