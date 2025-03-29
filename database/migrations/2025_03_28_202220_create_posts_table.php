<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parceiro_id'); // Relacionamento com a tabela parceiros
            $table->string('titulo');
            $table->text('conteudo');
            $table->string('imagem')->nullable(); // Campo de imagem
            $table->timestamps();
    
            // Adicionando a chave estrangeira para a tabela parceiros
            $table->foreign('parceiro_id')->references('id')->on('parceiros')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
