<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcorrenciaLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'ocorrencia_id', 'latitude', 'longitude'
    ];
}
