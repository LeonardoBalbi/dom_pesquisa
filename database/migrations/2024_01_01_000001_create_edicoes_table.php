<?php
// database/migrations/2024_01_01_000001_create_edicoes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edicoes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_edicao', 50);
            $table->date('data_publicacao');
            $table->string('arquivo_path', 255);
            $table->longText('conteudo_indexado')->nullable();
            $table->string('palavras_chave', 500)->nullable();
            $table->unsignedInteger('visualizacoes')->default(0);
            $table->timestamps();

            $table->index('data_publicacao');
            $table->fullText(['conteudo_indexado', 'palavras_chave']); // MATCH(conteudo_indexado, palavras_chave) — ver EdicaoPublicController@index
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edicoes');
    }
};
