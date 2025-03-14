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
                    <p class="{{ $ocorrencia->tipo != 'O' ? 'd-none' : '' }}">
                        <strong>Localização:</strong> <span class="text-muted">{{ $ocorrencia->localizacao }}</span>
                    </p>
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

{{-- CSS para formatação no print --}}
<style>
    .ocorrencia-img {
        max-width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 7px;
        border: 5px solid white;
    }

    /* Estilos aplicados SOMENTE no print */
    .print-mode {
        width: 1080px;
        height: 1920px;
        background: linear-gradient(to bottom, #0D6EFD, #1A73E8);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
        text-align: center;
        color: white;
        font-size: 24px;
        font-weight: bold;
        border-radius: 20px;
    }

    .print-mode h2 {
        font-size: 42px;
        font-weight: 700;
    }

    .print-mode p {
        font-size: 28px;
        max-width: 80%;
        margin-bottom: 20px;
    }

    .print-mode .ocorrencia-img {
        width: 90%;
        max-width: 900px;
        height: auto;
    }
</style>

{{-- JavaScript para Capturar e Compartilhar a Imagem --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('btnCompartilhar').addEventListener('click', function () {
        let card = document.getElementById('ocorrenciaCard');

        // Criar um container temporário para o print
        let printDiv = document.createElement('div');
        printDiv.classList.add('print-mode');

        // Criar elementos para o print
        let title = document.createElement('h2');
        title.innerText = "📢 Detalhes da Ocorrência";

        let details = document.createElement('p');
        details.innerHTML = `
            <strong>Título:</strong> {{ $ocorrencia->titulo }} <br>
            <strong>Descrição:</strong> {{ $ocorrencia->descricao }} <br>
            <strong>Localização:</strong> {{ $ocorrencia->localizacao }} <br>
            <strong>Publicado em:</strong> {{ $ocorrencia->created_at ? $ocorrencia->created_at->format('d/m/Y H:i') : 'Data não disponível' }}
        `;

        let img = document.createElement('img');
        img.src = "{{ asset('storage/' . $ocorrencia->imagem) }}";
        img.classList.add('ocorrencia-img');

        // Adicionar elementos ao container
        printDiv.appendChild(title);
        printDiv.appendChild(details);
        if (img.src) printDiv.appendChild(img);

        // Adicionar ao body e capturar imagem
        document.body.appendChild(printDiv);

        html2canvas(printDiv, { scale: 2 }).then(canvas => {
            let imgData = canvas.toDataURL('image/png');
            
            // Remover div temporária
            document.body.removeChild(printDiv);

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
