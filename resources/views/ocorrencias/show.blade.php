@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 exibir_conteudo">
            {{-- Card de Ocorrência --}}
            <div class="card shadow-sm p-4">
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
                        <img src="{{ asset('storage/' . $ocorrencia->imagem) }}" class="img-fluid rounded shadow">
                    </div>
                @endif

                <hr>

                {{-- Botão de Editar (Somente visível para o usuário que publicou a ocorrência) --}}
                @if(Auth::check() && Auth::user()->id === $ocorrencia->user_id)
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('ocorrencias.edit', $ocorrencia->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Editar Ocorrência
                        </a>
                    </div>
                    <hr>
                @endif

                {{-- Seção de Comentários --}}
                <h3 class="mb-3">Comentários</h3>

                {{-- Formulário de Comentários (Somente para usuários logados) --}}
                @auth
                <form action="{{ route('comentarios.store', $ocorrencia->id) }}" method="POST">
                    @csrf
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Deixe seu comentário</label>
                            <textarea name="comentario" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Enviar</button>
                    </form>
                @else
                    <p><a href="{{ route('login') }}" class="text-decoration-none">Faça login</a> para comentar.</p>
                @endauth

                <hr>

                {{-- Lista de Comentários --}}
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

<style>
    /* Estilo para telas maiores */
    .exibir_conteudo {
        padding-top: 8rem;
        padding-bottom: 8rem;
    }

    /* Estilo para telas pequenas (máximo de 767px) */
    @media (max-width: 767px) {
        .exibir_conteudo {
            padding-top: 6rem;
            padding-bottom: 6rem;
        }

        .card {
            padding: 1.5rem;
        }

        h2, h3 {
            font-size: 1.25rem;  /* Ajuste o tamanho do título para telas pequenas */
        }

        .btn {
            font-size: 0.875rem;  /* Reduz o tamanho do botão */
        }

        .mb-4 {
            margin-bottom: 1.5rem;  /* Ajuste do espaçamento para telas menores */
        }
    }

    /* Ajuste das bordas e sombras dos comentários */
    .card, .border {
        border-radius: 8px;
    }

    .shadow-sm {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Estilo para destacar a data dos comentários */
    .mb-3 small {
        font-style: italic;
        color: #6c757d;
    }
</style>

@endsection
