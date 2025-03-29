<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['partner_id', 'titulo', 'conteudo', 'imagem'];

    // Relacionamento inverso com Parceiro
    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class, 'parceiro_id'); // Verifique se o nome da coluna está correto
    }
}
