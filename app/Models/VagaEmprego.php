<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VagaEmprego extends Model
{
    use HasFactory;

    // Definindo o nome da tabela
    protected $table = 'vagas_emprego';

    // Definindo os campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'titulo',
        'descricao',
        'localizacao',
        'modalidade',
        'periodo',
        'tipo_contrato',
        'quantidade',
        'requisitos',
        'beneficios',
        'origem',
        'link',
    ];

    // Definindo os campos que devem ser tratados como timestamps
    public $timestamps = true;
}
