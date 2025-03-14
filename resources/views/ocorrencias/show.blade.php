@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 exibir_conteudo">
            {{-- Card de Ocorrência --}}
            <div class="card shadow-sm p-4" id="ocorrenciaCard">
                {{-- Ícone de voltar --}}
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('ocorrencias.index') }}" class="text-decoration-none text-dark">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h2 class="ms-2 mb-0 fs-5 fs-md-3">Detalhes da {{ $tituloOcorrencia }}</h2>
                </div>

                {{-- Detalhes da ocorrência --}}
                <div class="mb-3">
                    <p><strong>Título:</strong> <span class="text-muted">{{ $ocorrencia->titulo }}</span></p>
                    <p><strong>Descrição:</strong> <span class="text-muted">{{ $ocorrencia->descricao }}</span></p>
                    <p class="{{ $ocorrencia->tipo != 'O' ? 'd-none' : '' }}"><strong>Localização:</strong> <span class="text-muted">{{ $ocorrencia->localizacao }}</span></p>
                    <p><strong>Publicado em:</strong> <span class="text-muted">
                        {{ $ocorrencia->created_at ? $ocorrencia->created_at->format('d/m/Y H:i') : 'Data não disponível' }}
                    </span></p>
                </div>

                @if($ocorrencia->imagem)
                    <div class="text-center my-4">
                        <img src="{{ asset('storage/' . $ocorrencia->imagem) }}" class="img-fluid ocorrencia-img shadow">
                    </div>
                @endif

                <hr>

                {{-- Ícone de Compartilhar --}}
                <div class="d-flex justify-content-center mb-3">
                    <button id="btnCompartilhar" class="btn btn-secondary">
                        <i class="fas fa-share-alt"></i> Compartilhar Ocorrência
                    </button>
                </div>

                <hr>

                {{-- Lista de Comentários --}}
                <h3 class="mb-3">Comentários</h3>
                @forelse ($ocorrencia->comentarios as $comentario)
                    <div class="mb-3 border p-4 rounded bg-light shadow-sm">
                        <p><strong>{{ $comentario->usuario->name ?? 'Usuário Anônimo' }}</strong> comentou:</p>
                        <p>{{ $comentario->comentario }}</p>
                        <small class="text-muted">{{ $comentario->created_at->diffForHumans() }} ({{ $comentario->created_at->format('d/m/Y H:i') }})</small>
                    </div>
                @empty
                    <p>Não há comentários ainda.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- CSS para visualização normal --}}
<style>
    .ocorrencia-img {
        max-width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Estilos aplicados SOMENTE no print */
    .story-mode {
        width: 1080px !important;
        height: 1920px !important;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 80px 60px;
        border-radius: 20px;
    }

    .story-mode h2 {
        font-size: 60px !important;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .story-mode p {
        font-size: 40px;
        color: #555;
        line-height: 1.3;
    }

    .story-mode img {
        max-width: 85%;
        max-height: 60%;
        object-fit: cover;
        border-radius: 20px;
        margin: 20px 0;
    }

    .story-footer {
        font-size: 35px;
        font-weight: bold;
        margin-top: 30px;
        color: #222;
    }

    .story-logo {
        position: absolute;
        top: 40px;
        left: 50%;
        transform: translateX(-50%);
        width: 250px;
    }
</style>

{{-- JavaScript para Capturar e Compartilhar a Imagem --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('btnCompartilhar').addEventListener('click', function () {
        let card = document.getElementById('ocorrenciaCard');

        // Criar elementos extras para o Story
        let logo = document.createElement('img');
        logo.src = "{{ asset('storage/logo.png') }}"; // Caminho do logo
        logo.className = 'story-logo';

        let footer = document.createElement('p');
        footer.className = 'story-footer';
        footer.innerText = "UaiResolve - Juntos pela Cidade!";

        // Salvar estilos originais
        let originalClass = card.className;

        // Aplicar estilos de Story
        card.classList.add('story-mode');

        // Adicionar elementos temporários
        card.prepend(logo);
        card.appendChild(footer);

        // Gerar a imagem
        html2canvas(card, {
            width: 1080,
            height: 1920,
            scale: 2
        }).then(canvas => {
            let imgData = canvas.toDataURL('image/png');

            // Restaurar estilos e remover elementos extras
            card.className = originalClass;
            logo.remove();
            footer.remove();

            // Criar um link para baixar a imagem
            let link = document.createElement('a');
            link.href = imgData;
            link.download = 'ocorrencia_story.png';
            link.click();

            // Se o navegador suportar Web Share API, permitir compartilhamento direto
            if (navigator.share) {
                canvas.toBlob(blob => {
                    let file = new File([blob], "ocorrencia_story.png", { type: "image/png" });
                    let filesArray = [file];

                    navigator.share({
                        title: "Confira esta ocorrência!",
                        text: "Veja essa ocorrência no UaiResolve.",
                        files: filesArray
                    }).catch(error => console.log('Erro ao compartilhar:', error));
                });
            }
        });
    });
</script>

@endsection
