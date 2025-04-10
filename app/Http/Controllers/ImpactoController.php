<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ImpactoController extends Controller
{
    public function index()
    {
        // Cache por 10 min pra aliviar o banco
        $dados = Cache::remember('dashboard_publica', now()->addMinutes(10), function () {

            // Traz o nome da categoria direto do banco via JOIN
            $por_tipo = DB::table('ocorrencias')
                ->join('categorias', 'ocorrencias.categoria_id', '=', 'categorias.id')
                ->select('categorias.nome as categoria_nome', 'ocorrencias.categoria_id', DB::raw('count(*) as total'))
                ->groupBy('ocorrencias.categoria_id', 'categorias.nome')
                ->get();

            // Subdados: porcentagem de resolvidas por tipo
            $por_tipo_resolvidas = DB::table('ocorrencias')
                ->join('categorias', 'ocorrencias.categoria_id', '=', 'categorias.id')
                ->select(
                    'categorias.nome as nome_categoria',
                    'ocorrencias.categoria_id',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "Resolvida" THEN 1 ELSE 0 END) as resolvidas')
                )
                ->groupBy('ocorrencias.categoria_id', 'categorias.nome')
                ->get();
            // Ocorrências dos últimos 15 dias com relacionamento
            $evolucao_diaria = Ocorrencia::with('categoria')
                ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(14))
                ->groupByRaw('DATE(created_at)')
                ->orderBy('dia')
                ->get();

            return [
                'total_ocorrencias' => Ocorrencia::count(),
                'resolvidas' => Ocorrencia::where('status', 'Resolvida')->count(),
                'em_aberto' => Ocorrencia::where('status', '!=', 'Resolvida')->count(),
                'hoje' => Ocorrencia::whereDate('created_at', today())->count(),
                'por_tipo' => $por_tipo,
                'por_tipo_resolvidas' => $por_tipo_resolvidas,
                'evolucao_diaria' => $evolucao_diaria,
            ];
        });

        return view('impacto.index', compact('dados'));
    }
}
