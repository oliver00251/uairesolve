@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 exibir_conteudo">
            {{-- Card de Edição --}}
            <div class="card shadow-sm p-4">
                {{-- Ícone de voltar --}}
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('ocorrencias.index') }}" class="text-decoration-none text-dark">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h2 class="ms-2 mb-0 fs-5 fs-md-3">Editar {{$ocorrencia->titulo}}</h2>
                </div>

                {{-- Verificando se o usuário é o autor da ocorrência ou um administrador --}}
                @if(Auth::check() && (Auth::user()->id === $ocorrencia->user_id || Auth::user()->is_admin))
                    <form action="{{ route('ocorrencias.update', $ocorrencia->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <label for="status" class="form-label">Ações</label>

                        <select name="status" id="status" class="form-select" required>
                            <option value="Aberta" {{ old('status', $ocorrencia->status) == 'Aberta' ? 'selected' : '' }}>Aberta</option>
                            <option value="Em andamento" {{ old('status', $ocorrencia->status) == 'Em andamento' ? 'selected' : '' }}>Em andamento</option>
                            <option value="Resolvida" {{ old('status', $ocorrencia->status) == 'Resolvida' ? 'selected' : '' }}>Resolvida</option>
                            <option value="Excluir" {{ old('status', $ocorrencia->status) == 'Excluir' ? 'selected' : '' }}>Excluir</option>
                        </select>
                        
                        {{-- Título --}}
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $ocorrencia->titulo) }}" required>
                        </div>

                        {{-- Descrição --}}
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea name="descricao" id="descricao" class="form-control" rows="4" required>{{ old('descricao', $ocorrencia->descricao) }}</textarea>
                        </div>

                        {{-- Localização (se aplicável) --}}
                        @if($ocorrencia->tipo == 'O') 
                            <div class="mb-3">
                                <label for="localizacao" class="form-label">Localização</label>
                                <input type="text" name="localizacao" id="localizacao" class="form-control" value="{{ old('localizacao', $ocorrencia->localizacao) }}">
                            </div>
                        @endif

                        {{-- Imagem (se houver) --}}
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem (opcional)</label>
                            <input type="file" name="imagem" id="imagem" class="form-control">
                        </div>

                        {{-- Botão de Submissão --}}
                        <button type="submit" class="btn btn-success w-100">Salvar Alterações</button>
                    </form>
                @else
                    <p class="text-danger">Você não tem permissão para editar este conteúdo.</p>
                @endif
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

    /* Ajuste das bordas e sombras do formulário */
    .card, .border {
        border-radius: 8px;
    }

    .shadow-sm {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>

@endsection
