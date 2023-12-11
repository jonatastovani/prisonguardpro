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
        Schema::create('ref_permissao_config', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permissao_id')->nullable();
            $table->boolean('permite_substituto_bln')->default(false);
            $table->foreign('permissao_id')->references('id')->on('ref_permissao');
            $table->unsignedBigInteger('permissao_pai_id')->nullable();
            $table->foreign('permissao_pai_id')->references('id')->on('ref_permissao');
            $table->unsignedBigInteger('grupo_pai_id')->nullable();
            $table->foreign('grupo_pai_id')->references('id')->on('ref_permissao_grupo');
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->foreign('grupo_id')->references('id')->on('ref_permissao_grupo');
            $table->integer('ordem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_permissao_config');
    }
};
