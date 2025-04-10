@extends('layouts.app')

@section('title', 'Impacto Social')

@section('content')
    <div class="container py-5">

        <h1 class="mb-4 fw-bold text-primary">Impacto Social</h1>

        {{-- Estilos modernos --}}
        <style>
            .card-custom {
                background: #fff;
                border-radius: 12px;
                padding: 25px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
                transition: 0.3s;
                text-align: center;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .card-custom:hover {
                transform: translateY(-4px);
            }

            .card-custom i {
                font-size: 2.5rem;
                margin-bottom: 12px;
            }

            .card-label {
                font-weight: 500;
                font-size: 1rem;
                color: #6c757d;
            }

            .card-value {
                font-size: 1.8rem;
                font-weight: 700;
                color: #212529;
            }

            .card-tipo {
                background: #fff;
                border-left: 5px solid #0d6efd;
                border-radius: 10px;
                padding: 20px;
                transition: 0.3s ease;
                height: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .card-tipo:hover {
                transform: translateY(-4px);
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            }

            .tipo-label {
                font-weight: 600;
                font-size: 1rem;
                color: #444;
            }

            .tipo-count {
                font-size: 1.5rem;
                font-weight: bold;
                color: #0d6efd;
            }

            .table thead th {
                background-color: #f8f9fa;
                font-weight: 600;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: #f9f9f9;
            }

            body {
                background: #f8f9fa;
            }

            hr {
                transition: all 0.3s ease;
            }

            hr:hover {
                border-color: #0d6efd;
            }
        </style>

        {{-- Indicadores principais --}}
        <div class="row g-4 mb-5">
            @php
                $cards = [
                    ['label' => 'Total de Ocorrências', 'valor' => $dados['total_ocorrencias'], 'cor' => '#00bfa6', 'icone' => 'fa-list'],
                    ['label' => 'Resolvidas', 'valor' => $dados['resolvidas'], 'cor' => '#00c292', 'icone' => 'fa-check-circle'],
                    ['label' => 'Pendentes', 'valor' => $dados['em_aberto'], 'cor' => '#fb9678', 'icone' => 'fa-hourglass-half'],
                    ['label' => 'Hoje', 'valor' => $dados['hoje'], 'cor' => '#009efb', 'icone' => 'fa-calendar-day'],
                    
                ];
            @endphp
        
            @foreach ($cards as $card)
                <div class="col-6 col-md-3">
                    <div class="card-custom text-center">
                        <i class="fas {{ $card['icone'] }}" style="color: {{ $card['cor'] }}; font-size: 1.5rem;"></i>
                        <div class="card-label mt-2">{{ $card['label'] }}</div>
                        <div class="card-value fw-bold fs-4">{{ $card['valor'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        

        {{-- Indicadores de Acesso --}}
        <h4 class="mb-4 fw-bold text-secondary">👥 Acesso e Engajamento</h4>
        <div class="row g-4 mb-5">
            @php
                $acessos = [
                    ['label' => 'Visitas Totais', 'valor' => $dados['visitas_totais'], 'cor' => '#6f42c1', 'icone' => 'fa-eye'],
                    ['label' => 'Visitantes Únicos', 'valor' => $dados['visitantes_unicos'], 'cor' => '#fd7e14', 'icone' => 'fa-user-friends'],
                ];
            @endphp
        
            @foreach ($acessos as $card)
                <div class="col-6 col-md-3">
                    <div class="card-custom text-center">
                        <i class="fas {{ $card['icone'] }}" style="color: {{ $card['cor'] }}; font-size: 1.5rem;"></i>
                        <div class="card-label mt-2">{{ $card['label'] }}</div>
                        <div class="card-value fw-bold fs-4">{{ $card['valor'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        

        {{-- Ocorrências por tipo --}}
        <hr class="my-5 border-top border-2 border-primary-subtle">
        <h4 class="mb-4 fw-bold text-secondary">📊 Distribuição por Tipo</h4>
        <div class="row g-3 mb-5">
            @foreach ($dados['por_tipo'] as $tipo)
                <div class="col-md-4 col-sm-6">
                    <div class="card-tipo">
                        <div class="tipo-label">{{ ucfirst($tipo->categoria_nome) }}</div>
                        <div class="tipo-count">{{ $tipo->total }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Eficiência por Tipo --}}
        <hr class="my-5 border-top border-2 border-primary-subtle">
        <h4 class="mb-3 fw-bold text-secondary">⚙️ Eficiência por Tipo</h4>
        <div class="table-responsive mb-5">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Total</th>
                        <th>Resolvidas</th>
                        <th>% Resolvidas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dados['por_tipo_resolvidas'] as $tipo)
                        @php
                            $percentual = $tipo->total > 0 ? round(($tipo->resolvidas / $tipo->total) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td>{{ $tipo->nome_categoria }}</td>
                            <td>{{ $tipo->total }}</td>
                            <td class="text-success fw-semibold">{{ $tipo->resolvidas }}</td>
                            <td>
                                <span class="badge bg-{{ $percentual >= 70 ? 'success' : ($percentual >= 40 ? 'warning' : 'danger') }}">
                                    {{ $percentual }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Evolução dos Últimos 15 Dias --}}
        <hr class="my-5 border-top border-2 border-primary-subtle">
        <h4 class="mb-3 fw-bold text-secondary">📈 Evolução dos Últimos 15 Dias</h4>
        <canvas id="graficoEvolucao" height="100"></canvas>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('graficoEvolucao').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(
                    $dados['evolucao_diaria']->pluck('dia')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m')),
                ) !!},
                datasets: [{
                    label: 'Ocorrências por Dia',
                    data: {!! json_encode($dados['evolucao_diaria']->pluck('total')) !!},
                    fill: true,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    tension: 0.3
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
