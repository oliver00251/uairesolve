@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            {{-- Card de Ocorrência --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" id="ocorrenciaCard">
                
                {{-- Cabeçalho do Card com Imagem --}}
                @if($ocorrencia->imagem)
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $ocorrencia->imagem) }}" class="img-fluid w-100 card-img-top" alt="Imagem da Ocorrência">
                </div>
                @endif

                {{-- Corpo do Card --}}
                <div class="card-body p-4">
                    {{-- Ícone de voltar --}}
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('ocorrencias.index') }}" class="text-decoration-none text-dark">
                            <i class="fas fa-arrow-left fa-lg"></i>
                        </a>
                        <h2 class="ms-2 mb-0 fs-5 fw-bold">Detalhes da {{ $tituloOcorrencia }}</h2>
                    </div>

                    {{-- Detalhes da Ocorrência --}}
                    <div class="mb-4">
                        <p><strong class="text-primary">Título:</strong> <span class="text-muted">{{ $ocorrencia->titulo }}</span></p>
                        <p><strong class="text-primary">Descrição:</strong> <span class="text-muted">{{ $ocorrencia->descricao }}</span></p>
                        @if($ocorrencia->tipo == 'O')
                        <p><strong class="text-primary">Localização:</strong> <span class="text-muted">{{ $ocorrencia->localizacao }}</span></p>
                        @endif
                        <p><strong class="text-primary">Publicado em:</strong> <span class="text-muted">
                            {{ $ocorrencia->created_at ? $ocorrencia->created_at->format('d/m/Y H:i') : 'Data não disponível' }}
                        </span></p>
                    </div>

                    {{-- Botão de Compartilhar --}}
                    <div class="d-flex justify-content-center">
                        <button id="btnCompartilhar" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-share-alt"></i> Compartilhar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Estilos Personalizados --}}
<style>
    .card-img-top {
        object-fit: cover;
        height: 220px;
        border-radius: 8px 8px 0 0;
    }

    .btn-outline-primary {
        transition: 0.3s ease-in-out;
    }

    .btn-outline-primary:hover {
        background: #0D6EFD;
        color: white;
    }
</style>

{{-- Script de Compartilhamento --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('btnCompartilhar').addEventListener('click', function () {
        let card = document.getElementById('ocorrenciaCard');

        html2canvas(card, { scale: 2 }).then(canvas => {
            let imgData = canvas.toDataURL('image/png');
            let link = document.createElement('a');
            link.href = imgData;
            link.download = 'ocorrencia_story.png';
            link.click();
        });
    });
</script>

@endsection
