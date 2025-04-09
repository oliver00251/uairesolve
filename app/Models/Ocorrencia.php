<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'tipo',
        'localizacao',
        'user_id',
        'categoria_id',
        'status',
        'imagem',
        'link_id',
        'cidade_id',
        'bairro_id'
    ];

    // Comentários com relacionamento ao usuário
    public function comentarios()
    {
        return $this->hasMany(Comentario::class)->with('usuario');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function likes()
    {
        return $this->hasMany(OcorrenciaLike::class);
    }

    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(OcorrenciaStatusLog::class);
    }

    public function ultimoStatusLog()
    {
        return $this->hasOne(OcorrenciaStatusLog::class)->latestOfMany();
    }

    public function logStatusChange($novoStatus, $comentario = null, $alteradoPor = null)
    {
        $statusAnterior = $this->status;

        if ($statusAnterior === $novoStatus) {
            return;
        }

        $this->status = $novoStatus;
        $this->save();

        $this->statusLogs()->create([
            'status_anterior' => $statusAnterior,
            'status_novo'     => $novoStatus,
            'comentario'      => $comentario,
            'alterado_por'    => $alteradoPor,
        ]);
    }

    public function bairro()
    {
        return $this->belongsTo(Bairro::class);
    }

    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }
}
