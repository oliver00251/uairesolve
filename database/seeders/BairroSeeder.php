<?php

namespace Database\Seeders;

use App\Models\Bairro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BairroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bairro::create([
            'cidade_id' => 1,
            'nome' => 'Centro'
        ]);
    }
}
