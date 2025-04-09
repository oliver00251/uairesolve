<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcorrenciaStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ocorrencia_id',
        'status_anterior',
        'status_novo',
        'comentario',
        'alterado_por',
    ];

    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }
}
