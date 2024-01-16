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
        Schema::dropIfExists('pessoa_documentos');
    }
};
