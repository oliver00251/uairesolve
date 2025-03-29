<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Parceiro extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nome', 'slug', 'descricao', 'logo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'parceiro_id'); // A chave estrangeira é 'parceiro_id'
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($parceiro) {
            $parceiro->slug = self::generateUniqueSlug($parceiro->nome);
        });

        static::updating(function ($parceiro) {
            $parceiro->slug = self::generateUniqueSlug($parceiro->nome, $parceiro->id);
        });
    }

    /**
     * Gera um slug único para evitar duplicidade
     */
    private static function generateUniqueSlug($nome, $id = null)
    {
        $slug = Str::slug($nome);
        $count = 1;

        while (self::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = Str::slug($nome) . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
