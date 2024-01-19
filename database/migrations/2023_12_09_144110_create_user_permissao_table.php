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
        Schema::create('user_permissao', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('permissao_id');
            $table->foreign('permissao_id')->references('id')->on('ref_permissao');

            $table->boolean('substituto_bln')->default(false); // True para permissão que é substituto de alguma permissão que compete a diretor

            $table->date('data_inicio');
            $table->date('data_termino')->nullable();

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
        Schema::dropIfExists('user_permissao');
    }
};
