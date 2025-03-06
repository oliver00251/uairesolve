<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ocorrencia_id', 'comentario'];

    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','email');
    }
}
