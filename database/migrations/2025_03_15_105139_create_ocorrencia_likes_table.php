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
    Schema::create('ocorrencia_likes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ocorrencia_id')->constrained()->onDelete('cascade'); // Relaciona com a tabela de ocorrências
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relaciona com o usuário
        $table->timestamps();
        $table->unique(['ocorrencia_id', 'user_id']); // Garante que um usuário só possa curtir uma vez
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ocorrencia_likes');
    }
};
