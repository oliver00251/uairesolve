@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 exibir_conteudo">
            {{-- Card de Ocorrência --}}
            <div class="card shadow-sm p-4">
                {{-- Ícone de voltar --}}
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('ocorrencias.index') }}" class="text-decoration-none text-dark">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h2 class="ms-2 mb-0 fs-4 fs-md-3">Detalhes da Ocorrência</h2>
                </div>

                {{-- Detalhes da ocorrência --}}
                <p><strong>Título:</strong> {{ $ocorrencia->titulo }}</p>
                <p><strong>Descrição:</strong> {{ $ocorrencia->descricao }}</p>
                <p><strong>Localização:</strong> {{ $ocorrencia->localizacao }}</p>
                <p><strong>Publicado em:</strong> {{ $ocorrencia->created_at->format('d/m/Y H:i') }}</p>

                @if($ocorrencia->imagem)
                    <div class="text-center my-3">
                        <img src="{{ asset('storage/' . $ocorrencia->imagem) }}" class="img-fluid rounded shadow">
                    </div>
                @endif

                <hr>

                {{-- Seção de Comentários --}}
                <h3>Comentários</h3>

                {{-- Formulário de Comentários (Somente para usuários logados) --}}
                @auth
                    <form action="{{ route('comentarios.store', $ocorrencia) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Deixe seu comentário</label>
                            <textarea name="comentario" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Enviar</button>
                    </form>
                @else
                    <p><a href="{{ route('login') }}">Faça login</a> para comentar.</p>
                @endauth

                <hr>

                {{-- Lista de Comentários --}}
                @forelse ($ocorrencia->comentarios as $comentario)
                    <div class="mb-3 border p-3 rounded bg-light">
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
   
    /* Estilo para telas pequenas (máximo de 767px) */
    @media (max-width: 767px) {
        .exibir_conteudo {
            padding-top: 6rem;
            padding-bottom: 6rem;
        }

       
        
    }
</style>

@endsection
