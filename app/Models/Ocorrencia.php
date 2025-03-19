<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descricao', 'tipo', 'localizacao', 'user_id', 'categoria_id', 'status', 'imagem', 'link_id'];

    // Relacionamento com Comentários
    public function comentarios()
    {
        return $this->hasMany(Comentario::class)->with('usuario');
    }
    // Relacionamento com Comentários
    public function categoria()
{
    return $this->belongsTo(Categoria::class, 'categoria_id');
}
    // Relacionamento com Likes
    public function likes()
    {
        return $this->hasMany(OcorrenciaLike::class);
    }

    // Verifica se a Ocorrencia foi curtida pelo usuário
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // Relacionamento com o Usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com o Link
    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id');
    }
}
