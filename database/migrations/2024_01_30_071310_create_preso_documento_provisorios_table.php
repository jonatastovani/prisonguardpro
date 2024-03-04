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
        Schema::create('preso_documento_provisorios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('passagem_id');
            $table->foreign('passagem_id')->references('id')->on('inc_entrada_presos');

            $table->unsignedBigInteger('documento_id')->nullable();
            $table->foreign('documento_id')->references('id')->on('ref_documento_configs');

            $table->string('numero', 50);
            $table->string('digito', 5)->nullable();
            $table->date('data_emissao')->nullable();

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
        Schema::dropIfExists('preso_documento_provisorios');
    }
};
