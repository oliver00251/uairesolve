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
        Schema::create('ocorrencia_status_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ocorrencia_id');
            $table->string('status_anterior')->nullable();
            $table->string('status_novo');
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('alterado_por')->nullable(); // pode ser user_id ou admin_id
            $table->timestamps();
    
            $table->foreign('ocorrencia_id')->references('id')->on('ocorrencias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ocorrencia_status_logs');
    }
};
