<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descricao', 'tipo', 'localizacao', 'user_id', 'categoria_id', 'status', 'imagem'];

    public function comentarios()
    {
        return $this->hasMany(Comentario::class)->with('usuario');
    }
    public function likes()
    {
        return $this->hasMany(OcorrenciaLike::class);
    }

    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
    // No modelo Ocorrencia
public function user()
{
    return $this->belongsTo(User::class);
}

}
