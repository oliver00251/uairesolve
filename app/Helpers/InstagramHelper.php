<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class InstagramHelper
{
    public static function getInstagramPosts($username, $limit = 6)
    {
        // Cache por 1 hora para evitar bloqueios do Instagram
        return Cache::remember("instagram_posts_{$username}", 3600, function () use ($username, $limit) {
            $url = "https://www.instagram.com/{$username}/";
            
            // Requisição HTTP sem verificação SSL
            $response = Http::withoutVerifying()->get($url);
            
            // Verificar se a requisição falhou
            if ($response->failed()) {
                return [];
            }

            // Extrair os shortcodes dos posts
            preg_match_all('/"shortcode":"(.*?)"/', $response->body(), $matches);

            // Gerar os links dos posts
            $posts = [];
            foreach ($matches[1] as $shortcode) {
                $posts[] = "https://www.instagram.com/p/{$shortcode}/";
            }

            // Retornar apenas o número limitado de posts
            return array_slice($posts, 0, $limit);
        });
    }
}
