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
        Schema::create('presos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pessoa_id');
            $table->foreign('pessoa_id')->references('id')->on('pessoas');

            $table->string('matricula');
            $table->double('estatura', 3, 2);
            $table->double('peso', 5, 2);

            $table->unsignedBigInteger('cutis_id');
            $table->foreign('cutis_id')->references('id')->on('ref_cutis');

            $table->unsignedBigInteger('cabelo_tipo_id');
            $table->foreign('cabelo_tipo_id')->references('id')->on('ref_cabelo_tipos');

            $table->unsignedBigInteger('cabelo_cor_id');
            $table->foreign('cabelo_cor_id')->references('id')->on('ref_cabelo_cor');

            $table->unsignedBigInteger('olho_cor_id');
            $table->foreign('olho_cor_id')->references('id')->on('ref_olho_cor');

            $table->unsignedBigInteger('olho_tipo_id');
            $table->foreign('olho_tipo_id')->references('id')->on('ref_olho_tipos');

            $table->unsignedBigInteger('crenca_id');
            $table->foreign('crenca_id')->references('id')->on('ref_crencas');

            $table->text('sinais');
            
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
        Schema::dropIfExists('presos');
    }
};
