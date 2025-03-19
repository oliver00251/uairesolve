<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    // Definindo o nome da tabela (se for diferente da convenção)
    // protected $table = 'links';

    // Se você quiser especificar quais campos podem ser preenchidos em massa
    protected $fillable = ['url', 'descricao', 'categoria_id'];

    // Se você quiser proteger certos campos contra preenchimento em massa (mass assignment)
    // protected $guarded = ['id'];

    // Definindo o relacionamento com a categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
