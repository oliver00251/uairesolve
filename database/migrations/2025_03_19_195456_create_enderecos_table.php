<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecosTable extends Migration
{
    /**
     * Execute a migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('cep', 10);  // Adiciona a coluna cep
            $table->string('rua');      // Adiciona a coluna rua
            $table->string('bairro');   // Adiciona a coluna bairro
            $table->string('cidade');   // Adiciona a coluna cidade
            $table->string('estado', 2); // Adiciona a coluna estado (UF)
            $table->timestamps();
        });
    }

    /**
     * Revert a migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enderecos');
    }
}
