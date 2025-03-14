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
        Schema::create('ocorrencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('categoria_id')->nullable();
            $table->string('titulo');
            $table->text('descricao');
            $table->string('imagem')->nullable();
            $table->string('tipo',1); // O = Ocorrencia;S = sugestão; I=Indicação; D= Denuncias
            $table->string('localizacao')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['Aberta', 'Em andamento', 'Resolvida','Arquivada'])->default('Aberta');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ocorrencias');
    }
};
