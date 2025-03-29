<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parceiros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relaciona com a tabela users
            $table->string('nome'); // Nome da ONG ou empresa
            $table->text('descricao')->nullable(); // Breve descrição
            $table->string('logo')->nullable(); // Caminho da imagem do perfil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parceiros');
    }
};
