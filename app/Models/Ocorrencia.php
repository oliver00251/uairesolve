<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descricao','tipo', 'localizacao','user_id','categoria_id', 'status', 'imagem'];
    
    public function comentarios() {
        return $this->hasMany(Comentario::class)->with('usuario');
    }
    
}
