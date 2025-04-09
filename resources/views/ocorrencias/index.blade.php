@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-3">Ocorrências</h2>

    <div class="row">
       
        <!-- Filtro Menu -->
        @include('ocorrencias.menu.filtro')

        <!-- Lista de Ocorrências -->
        <div class="col-lg">
            <div class="row">
                @forelse($ocorrencias as $ocorrencia)
                    <div class="col-12 col-md-3 mb-3">
                        <div class="card ocorrencia-card h-100">
                            <div class="ocorrencia-capa-wrapper">
                                <img src="{{ $ocorrencia->imagem ? asset('storage/' . $ocorrencia->imagem) : 'https://uairesolve.com.br/storage/ocorrencias/67f676bcac663.png' }}"
                            class="ocorrencia-capa">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <!-- Cabeçalho: Categoria e Status -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-none text-dark">
                                        {{ $ocorrencia->categoria->nome ?? 'Outros' }}
                                    </span>
                                    @php
                                        $statusClass = match($ocorrencia->status) {
                                            'Aberta' => 'badge bg-primary',
                                            'Em andamento' => 'badge bg-warning text-dark',
                                            'Resolvida' => 'badge bg-success',
                                            'Arquivada' => 'badge bg-secondary',
                                            default => 'badge bg-dark'
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">
                                        Status: {{ $ocorrencia->status }}
                                    </span>
                                </div>

                                <!-- Título -->
                                <h5 class="card-title fw-bold">{{ $ocorrencia->titulo }}</h5>

                                <!-- Localização -->
                                <p class="text-muted mb-1">
                                    <i class="fas fa-map-marker-alt"></i> {{ $ocorrencia->localizacao ?? 'N/A' }}
                                </p>

                                <!-- Descrição -->
                                <p class="card-text mb-2">
                                    {{ Str::limit($ocorrencia->descricao, 80) }}
                                </p>

                                <!-- Datas: Criação e Última alteração de status -->
                                <div class="text-muted small mb-3">
                                    <div>Criado: {{ $ocorrencia->created_at->diffForHumans() }}</div>
                                    @if($ocorrencia->ultimoStatusLog)
                                        <div>
                                            Status alterado: {{ $ocorrencia->ultimoStatusLog->created_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Ação -->
                                <div class="mt-auto text-end">
                                    <a href="{{ route('ocorrencias.show', $ocorrencia) }}" class="btn btn-outline-primary btn-sm">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">Nenhuma ocorrência em aberto no momento.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Botão flutuante no mobile -->
<a href="{{ route('ocorrencias.create', ['tipo' => $filtro]) }}" class="btn btn-add-ocorrencia">
    <i class="fas fa-plus fa-lg text-white"></i>
</a>
@endsection

<style>
/* Cartão de Ocorrência */
.ocorrencia-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0px 4px 8px rgba(0, 245, 131, 0.1);
    transition: 0.3s;
}

a.btn.btn-add-ocorrencia {
    display: flex
;
    border-radius: 100%;
}

.ocorrencia-card:hover {
    transform: translateY(-2px);
}

/* Badge padrão para Categoria */
.badge.bg-dark {
    background: #05af0e;
    font-size: 0.75rem;
    padding: 5px 8px;
    border-radius: 12px;
}

/* Titulos e textos */
.card-title {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.card-text {
    font-size: 0.9rem;
    color: #444;
}

/* Botão flutuante */
.btn-add-ocorrencia {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: #0B5ED7 !important;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
}
.ocorrencia-capa-wrapper {
    height: 180px;
    overflow: hidden;
    border-top-left-radius: 0.375rem;  /* se quiser combinar com o card */
    border-top-right-radius: 0.375rem;
}

.ocorrencia-capa {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

</style>
