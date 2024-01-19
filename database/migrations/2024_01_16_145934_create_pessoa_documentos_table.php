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
        Schema::create('pessoa_documentos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pessoa_id');
            $table->foreign('pessoa_id')->references('id')->on('pessoas');

            $table->unsignedBigInteger('doc_tipo_id');
            $table->foreign('doc_tipo_id')->references('id')->on('ref_documento_tipos');
            
            $table->unsignedBigInteger('org_exp_id');
            $table->foreign('org_exp_id')->references('id')->on('ref_documento_orgao_emissor');

            $table->unsignedBigInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('ref_estados');

            $table->string('numero');
            
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
        Schema::dropIfExists('pessoa_documentos');
    }
};
