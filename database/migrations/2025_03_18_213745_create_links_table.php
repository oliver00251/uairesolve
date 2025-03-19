<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id(); // Cria uma coluna 'id' com auto incremento
            $table->string('url'); // Campo para o link
            $table->string('descricao'); // Campo para a descrição do link
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade'); // Relacionamento com a tabela de categorias
            $table->timestamps(); // Cria as colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}
