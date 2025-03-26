@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Ocorrências em Aberto</h2>

    <div class="row">
        <!-- Menu lateral (Desktop) -->
        @include('ocorrencias.menu.lateral')
        <!-- Filtro Mobile -->
        @include('ocorrencias.menu.mobile')


        <!-- Lista de Ocorrências -->
        <div class="col-lg-9">
            <div class="row">
                @forelse($ocorrencias as $ocorrencia)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5>{{ $ocorrencia->categoria->nome ?? 'Outros' }}</h5>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $ocorrencia->titulo }}</h5>
                                <p class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i> {{ $ocorrencia->localizacao ?? 'N/A' }}</p>
                                <p class="card-text">{{ Str::limit($ocorrencia->descricao, 100) }}</p>
                                <a href="{{ route('ocorrencias.show', $ocorrencia) }}" class="btn btn-primary btn-sm">Ver Detalhes</a>
                            </div>
                            <div class="card-footer">
                                <small>Publicado em: {{ $ocorrencia->created_at->format('d/m/Y H:i') }}</small>
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
<a href="{{ route('ocorrencias.create', ['tipo' => $filtro]) }}" class="btn rounded-circle shadow-lg position-fixed"
   style="bottom: 20px; right: 20px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background:#0B5ED7 !important">
    <i class="fas fa-plus fa-lg text-white"></i>
</a>

@endsection
