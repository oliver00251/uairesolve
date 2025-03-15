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

                    {{-- Imagem da ocorrência --}}
                    @if ($ocorrencia->imagem)
                        <div class="text-center my-4">
                            <img src="{{ asset('storage/' . $ocorrencia->imagem) }}" class="img-fluid ocorrencia-img shadow">
                        </div>
                    @endif

                    <hr>

                    {{-- Botão de Editar (Somente para o dono da ocorrência) --}}
                    @if (Auth::check() && Auth::user()->id === $ocorrencia->user_id)
                        <div class="d-flex justify-content-end" id="btnEditarOcorrencia">
                            <a href="{{ route('ocorrencias.edit', $ocorrencia->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar Ocorrência
                            </a>
                        </div>
                        <hr>
                    @endif

                    {{-- Botão de Compartilhar --}}
                    <div class="d-flex justify-content-center mb-3" id="btnCompartilharContainer">
                        <button id="btnCompartilhar" class="btn btn-secondary">
                            <i class="fas fa-share-alt"></i> Compartilhar Ocorrência
                        </button>
                    </div>

                    <hr>

                    {{-- Seção de Comentários --}}
                    <div class="comentarios-secao">
                        <h3 class="mb-3">Comentários</h3>

                        {{-- Lista de Comentários --}}
                        @forelse ($ocorrencia->comentarios as $comentario)
                            <div class="mb-3 border p-4 rounded bg-light shadow-sm">
                                <p><strong>{{ $comentario->usuario->name ?? 'Usuário Anônimo' }}</strong> comentou:</p>
                                <p>{{ $comentario->comentario }}</p>
                                <small class="text-muted">
                                    {{ $comentario->created_at->diffForHumans() }}
                                    ({{ $comentario->created_at->format('d/m/Y H:i') }})
                                </small>
                            </div>
                        @empty
                            <p>Não há comentários ainda.</p>
                        @endforelse

                        {{-- Se o usuário estiver logado, exibe o formulário de comentário --}}
                        @auth
                            <div class="comentarios-formulario mb-4">
                                <h4>Deixe seu comentário:</h4>
                                <form action="{{ route('comentarios.store', $ocorrencia->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="comentario" class="form-control" rows="4" placeholder="Escreva seu comentário..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Comentar</button>
                                </form>
                            </div>
                        @endauth

                        {{-- Se o usuário não estiver logado, exibe uma mensagem pedindo para fazer login --}}
                        @guest
                            <p>Para comentar, você precisa <a href="{{ route('login') }}">fazer login</a>.</p>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Visualização da Imagem e Download --}}
    <div class="modal fade" id="modalCompartilhar" tabindex="-1" aria-labelledby="modalCompartilharLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCompartilharLabel">Visualização da Ocorrência</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Imagem gerada será exibida aqui -->
                    <img id="imagemGeradaModal" src="" alt="Imagem Gerada" class="img-fluid mb-3" style="max-height: 300px; max-width: 100%;">

                    <!-- Botão de download da imagem -->
                    <div class="mt-3">
                        <button id="btnDownloadImagem" class="btn btn-success">
                            <i class="fas fa-download"></i> Baixar Imagem
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS para formatação no print --}}
    <style>
        .ocorrencia-img {
            width: 85%;
            max-width: 900px;
            object-fit: cover;
            border-radius: 15px;
            border: 8px solid white;
            margin-top: 20px;
        }

        /* Estilos aplicados SOMENTE no print */
        .print-mode {
            width: 1080px;
            height: 1920px;
            background: linear-gradient(to bottom, #0D6EFD, #90b2df);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            font-family: 'Arial', sans-serif;
            border-radius: 0px;
        }

        .print-mode h2 {
            font-size: 85px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 2rem;
            background: orange;
            margin: 4rem;
            border-radius: 1rem;
        }

        .print-mode p {
            font-size: 58px;
            max-width: 84%;
            margin-bottom: 30px;
            font-weight: 500;
            text-align: left;
        }

        .print-mode .ocorrencia-img {
            width: 90%;
            max-width: 900px;
            height: auto;
        }

        .exibir_conteudo {
            padding: 5.7rem 0.3rem;
        }

        @media (min-width: 768px) {
            .exibir_conteudo {
                padding: 5.7rem 0rem;
            }
        }
    </style>

    {{-- JavaScript para Capturar e Exibir a Imagem com Botão de Download --}}
    <script>
        document.getElementById('btnCompartilhar').addEventListener('click', function() {
            let comentarios = document.querySelector('.comentarios-secao');
            let btnCompartilharContainer = document.getElementById('btnCompartilharContainer');
            let btnEditarOcorrencia = document.getElementById('btnEditarOcorrencia');

            // Ocultar elementos antes do print
            if (comentarios) comentarios.style.display = 'none';
            if (btnCompartilharContainer) btnCompartilharContainer.style.display = 'none';
            if (btnEditarOcorrencia) btnEditarOcorrencia.style.display = 'none';

            // Criar um container temporário para o print
            let printDiv = document.createElement('div');
            printDiv.classList.add('print-mode');

            // Criar título
            let title = document.createElement('h2');
            title.innerText = "📢 Atenção";

            // Limitar a descrição às 14 primeiras palavras e adicionar a sugestão
            let descricao = "{{ $ocorrencia->descricao }}";
            let palavras = descricao.split(' '); // Divide a descrição em palavras
            let descricaoLimitada = palavras.slice(0, 14).join(' ') + (palavras.length > 14 ? '... Veja mais em uairesolve.com.br' : '');

            // Criar detalhes
            let details = document.createElement('p');
            details.innerHTML = `
                <strong>Título:</strong> {{ $ocorrencia->titulo }} <br>
                <strong>Descrição:</strong> ${descricaoLimitada} <br>
                <strong>Localização:</strong> {{ $ocorrencia->localizacao }} <br>
                <strong>Publicado em:</strong> {{ $ocorrencia->created_at ? $ocorrencia->created_at->format('d/m/Y H:i') : 'Data não disponível' }}
            `;

            // Criar imagem
            let img = document.createElement('img');
            img.src = "{{ asset('storage/' . $ocorrencia->imagem) }}";
            img.classList.add('ocorrencia-img');

            // Adicionar elementos ao printDiv
            printDiv.appendChild(title);
            printDiv.appendChild(details);
            if (img.src) printDiv.appendChild(img);

            // Adicionar ao body e capturar imagem
            document.body.appendChild(printDiv);

            // Agora que a div está no DOM, podemos capturar a imagem
            html2canvas(printDiv, { scale: 2 }).then(canvas => {
                let imgData = canvas.toDataURL('image/png');
                document.body.removeChild(printDiv);

                // Restaurar elementos ocultos
                if (comentarios) comentarios.style.display = 'block';
                if (btnEditarOcorrencia) btnEditarOcorrencia.style.display = 'flex';

                // Exibir o modal com a imagem gerada
                let modal = new bootstrap.Modal(document.getElementById('modalCompartilhar'));
                let imgModal = document.getElementById('imagemGeradaModal');
                imgModal.src = imgData;
                modal.show(); // Exibe o modal

                // Adicionar opção de download da imagem
                document.getElementById('btnDownloadImagem').addEventListener('click', function() {
                    let link = document.createElement('a');
                    link.href = imgData;
                    link.download = 'ocorrencia.png'; // Nome do arquivo de download
                    link.click();
                });
            });
        });
    </script>
@endsection
