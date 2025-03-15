<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;

    // Define a tabela associada a esse model (se não seguir o padrão plural)
    protected $table = 'access_logs';

    // Defina os campos que podem ser preenchidos em massa
    protected $fillable = [
        'ip_address',
        'user_agent',
        'access_time',
    ];

    // Se quiser que o Laravel gerencie a data automaticamente
    public $timestamps = true;

    // Caso queira formatar o campo de hora (opcional)
    protected $dates = [
        'access_time',
    ];
}
