<?php

namespace Database\Seeders;

use App\Models\Cidade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cidade::updateOrCreate(
            ['slug' => 'nepomuceno-mg'],
            [
                'nome' => 'Nepomuceno',
                'estado' => 'MG',
            ]
        );
    }
}
