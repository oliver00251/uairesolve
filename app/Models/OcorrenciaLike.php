<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcorrenciaLike extends Model
{
    use HasFactory;

    // Defina a tabela associada ao modelo
    protected $table = 'ocorrencia_likes';

    // Os campos que podem ser atribuídos em massa
    protected $fillable = [
        'ocorrencia_id',
        'user_id',
    ];

    // Definir os relacionamentos
    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
