<?php

namespace App\Console\Commands;

use App\Models\VagaEmprego;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GerarImagensVagasHoje extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gerar:imagens-vagas-hoje';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera imagens de todas as vagas criadas hoje';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
{
    $hoje = now()->toDateString(); // Ex: 2025-04-10

    $vagas = VagaEmprego::whereDate('created_at', $hoje)->get();

    if ($vagas->isEmpty()) {
        $this->info('Nenhuma vaga criada hoje.');
        return;
    }

    foreach ($vagas as $vaga) {
        $response = Http::post(env('MICROSERVICO_GERAR_IMAGEM'), [
            'titulo' => $vaga->titulo,
            'localizacao' => $vaga->localizacao,
            'periodo' => $vaga->periodo,
            'tipo_contrato' => $vaga->tipo_contrato,
            'requisitos' => $vaga->requisitos,
        ]);

        if ($response->successful()) {
            $this->info('Imagem gerada: ' . $response->json()['imagem']);
        } else {
            $this->error('Erro ao gerar imagem para vaga ID ' . $vaga->id);
        }
    }

    $this->info('Processo concluído.');
}
}
