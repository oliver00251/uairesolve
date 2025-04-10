<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Support\Facades\DB;

class ImpactoController extends Controller
{
    public function index()
    {
        // Ocorrências por tipo (com JOIN pra puxar o nome da categoria)
        $por_tipo = DB::table('ocorrencias')
            ->join('categorias', 'ocorrencias.categoria_id', '=', 'categorias.id')
            ->select(
                'categorias.nome as categoria_nome',
                'ocorrencias.categoria_id',
                DB::raw('count(*) as total')
            )
            ->groupBy('ocorrencias.categoria_id', 'categorias.nome')
            ->get();

        // Ocorrências resolvidas por tipo (com cálculo de % resolvidas)
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

        // Ocorrências dos últimos 15 dias (pra mostrar evolução no tempo)
        $evolucao_diaria = Ocorrencia::with('categoria')
            ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('dia')
            ->get();

            $ip_para_ignorar = '187.109.62.154';

            // Total de visitas (sem contar o IP ignorado)
            $total_visitas = DB::table('access_logs')
                ->where('ip_address', '!=', $ip_para_ignorar)
                ->count();
            
            // Visitantes únicos (sem contar o IP ignorado)
            $visitantes_unicos = DB::table('access_logs')
                ->where('ip_address', '!=', $ip_para_ignorar)
                ->select('ip_address')
                ->distinct()
                ->count();
    

        // Dados agregados que serão passados pra view
        $dados = [
            'total_ocorrencias' => Ocorrencia::count(),
            'resolvidas' => Ocorrencia::where('status', 'Resolvida')->count(),
            'em_aberto' => Ocorrencia::where('status', '!=', 'Resolvida')->count(),
            'hoje' => Ocorrencia::whereDate('created_at', today())->count(),
            'por_tipo' => $por_tipo,
            'por_tipo_resolvidas' => $por_tipo_resolvidas,
            'evolucao_diaria' => $evolucao_diaria,
            'total_visitas' => $total_visitas,
            'visitantes_unicos' => $visitantes_unicos,
        ];

        return view('impacto.index', compact('dados'));
    }
}
