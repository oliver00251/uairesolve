@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-3">Ocorrências em Aberto</h2>

    <div class="row">
        <!-- Menu lateral (Desktop) -->
        @include('ocorrencias.menu.lateral')
        <!-- Filtro Mobile -->
        @include('ocorrencias.menu.mobile')

        <!-- Lista de Ocorrências -->
        <div class="col-lg-9">
            <div class="row">
                @forelse($ocorrencias as $ocorrencia)
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card ocorrencia-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <span class="categoria-badge bg-success">{{ $ocorrencia->categoria->nome ?? 'Outros' }}</span>
                                    <small class="text-muted ms-auto">{{ $ocorrencia->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <h5 class="mt-2 card-title fw-bold">{{ $ocorrencia->titulo }}</h5>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-map-marker-alt"></i> {{ $ocorrencia->localizacao ?? 'N/A' }}
                                </p>
                                <p class="card-text">{{ Str::limit($ocorrencia->descricao, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('ocorrencias.show', $ocorrencia) }}" class="btn btn-primary btn-sm">Ver Detalhes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Nenhuma ocorrência em aberto no momento.</p>
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
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    transition: 0.3s;
}

.ocorrencia-card:hover {
    transform: translateY(-2px);
}

/* Badge da Categoria */
.categoria-badge {
   
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Botão flutuante para adicionar ocorrência */
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
a.btn.btn-add-ocorrencia {
    display: flex
;
    border-radius: 100%;
    margin-bottom: 3vh;
    opacity: 0.7;
}

</style>