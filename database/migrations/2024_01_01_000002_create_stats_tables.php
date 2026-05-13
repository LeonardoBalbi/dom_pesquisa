<?php
// database/migrations/2024_01_01_000002_create_visitas_diarias_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitas_diarias', function (Blueprint $table) {
            $table->date('data_visita')->primary();
            $table->unsignedInteger('quantidade')->default(0);
        });

        Schema::create('termos_pesquisados', function (Blueprint $table) {
            $table->id();
            // 191 caracteres: limite seguro para índice UNIQUE com utf8mb4 no InnoDB (767/1000 bytes)
            $table->string('termo', 191)->unique();
            $table->unsignedInteger('quantidade')->default(1);
            $table->timestamp('ultima_busca')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('login_tentativas', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->timestamp('tentativa_em')->useCurrent();

            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_tentativas');
        Schema::dropIfExists('termos_pesquisados');
        Schema::dropIfExists('visitas_diarias');
    }
};
