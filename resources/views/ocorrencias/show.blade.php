@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm p-4">

                {{-- Voltar e título --}}
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('ocorrencias.index') }}" class="text-decoration-none text-dark me-2">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h4 class="mb-0">Detalhes da {{ $ocorrencia->categoria->nome ?? 'Ocorrência' }}</h4>
                </div>

                {{-- Imagem de capa --}}
                <div class="mb-4">
                    <img src="{{ $ocorrencia->imagem ? asset('storage/' . $ocorrencia->imagem) : 'https://uairesolve.com.br/storage/ocorrencias/67f676bcac663.png' }}"
                         alt="Imagem da Ocorrência"
                         class="img-fluid rounded shadow ocorrencia-capa">
                </div>

                {{-- Título e Descrição --}}
                <h5 class="fw-bold">{{ $ocorrencia->titulo }}</h5>
                <p class="text-muted">{!! nl2br(e($ocorrencia->descricao)) !!}</p>

                {{-- Dados adicionais --}}
                <div class="mb-3">
                    <p class="{{ $ocorrencia->tipo != 'O' ? 'd-none' : '' }}">
                        <i class="fas fa-map-marker-alt text-danger"></i>
                        <strong> Localização:</strong> {{ $ocorrencia->localizacao ?? 'Não informada' }}
                    </p>

                    @if ($ocorrencia->link)
                        <p>
                            <i class="fas fa-link text-primary"></i>
                            <strong> Link:</strong>
                            <a href="{{ $ocorrencia->link->url }}" target="_blank" class="text-muted">{{ $ocorrencia->link->url }}</a>
                        </p>
                    @endif

                    <p class="text-muted">
                        <i class="fas fa-calendar-alt"></i>
                        <strong> Publicado em:</strong>
                        {{ $ocorrencia->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <hr>

                {{-- Ações --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    @if (Auth::check() && Auth::user()->id === $ocorrencia->user_id)
                        <a href="{{ route('ocorrencias.edit', $ocorrencia->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    @endif

                    <button id="btnCompartilhar" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-share-alt"></i> Compartilhar
                    </button>

                    @include('ocorrencias.btn-curtir')
                </div>

                @if (auth()->check() && auth()->user()->tipo == 'admin')
                    <a href="{{ route('ocorrencias.gera.image', ['id' => $ocorrencia->id]) }}" class="btn btn-sm btn-outline-success" download>
                        Baixar imagem da postagem
                    </a>
                    
                @endif

                <hr>

                {{-- Comentários --}}
                @include('ocorrencias.comentarios')

            </div>
        </div>
    </div>
</div>

@include('ocorrencias.modal-compartilhar')
@endsection
