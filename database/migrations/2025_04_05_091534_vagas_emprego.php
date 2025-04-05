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
        Schema::create('vagas_emprego', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('localizacao')->nullable();
            $table->string('modalidade')->nullable();
            $table->string('periodo')->nullable();
            $table->string('tipo_contrato')->nullable();
            $table->integer('quantidade')->nullable();
            $table->text('requisitos')->nullable();
            $table->text('beneficios')->nullable();
            $table->string('origem')->nullable();
            $table->string('link')->nullable(); // <-- Aqui tá o link da vaga
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vagas_emprego');
    }
};
