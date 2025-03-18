<?php

namespace Database\Seeders;  // Certifique-se de que o namespace esteja correto

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            ['nome' => 'Sugestão', 'descricao' => 'Sugestões gerais'],
            ['nome' => 'Vagas de emprego', 'descricao' => 'Oportunidades de trabalho'],
            ['nome' => 'Denúncias', 'descricao' => 'Relatos de irregularidades'],
            ['nome' => 'Ajuda', 'descricao' => 'Pedidos de assistência'],
            ['nome' => 'Causa Animal', 'descricao' => 'Proteção e resgate de animais'],
            ['nome' => 'Eventos', 'descricao' => 'Divulgação de eventos e encontros'],
            ['nome' => 'Notícias', 'descricao' => 'Informações e atualizações relevantes'],
            ['nome' => 'Educação', 'descricao' => 'Dicas, cursos e oportunidades de aprendizado'],
            ['nome' => 'Saúde', 'descricao' => 'Informações sobre bem-estar e saúde'],
            ['nome' => 'Tecnologia', 'descricao' => 'Novidades do mundo tech e inovações'],
            ['nome' => 'Esportes', 'descricao' => 'Discussões sobre esportes e atividades físicas'],
            ['nome' => 'Entretenimento', 'descricao' => 'Filmes, séries, música e lazer'],
            ['nome' => 'Política', 'descricao' => 'Discussões e notícias do cenário político'],
            ['nome' => 'Meio Ambiente', 'descricao' => 'Sustentabilidade e conservação ambiental'],
            ['nome' => 'Voluntariado', 'descricao' => 'Oportunidades para ajudar a comunidade'],
        ];

        DB::table('categorias')->insert($categorias);
    }
}
