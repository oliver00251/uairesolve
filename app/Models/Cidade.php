<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'estado',
        'slug',
    ];

    // Relacionamento com Ocorrências
    public function ocorrencias()
    {
        return $this->hasMany(Ocorrencia::class);
    }

    // Accessor para nome completo (opcional, mas elegante)
    public function getNomeCompletoAttribute()
    {
        return "{$this->nome} - {$this->estado}";
    }

    // Rota amigável (se quiser usar o slug como identificador)
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function bairros()
    {
        return $this->hasMany(Bairro::class);
    }
}
