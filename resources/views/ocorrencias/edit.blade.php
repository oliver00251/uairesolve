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
                    <form id="editOcorrenciaForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Status --}}
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Aberta" {{ old('status', $ocorrencia->status) == 'Aberta' ? 'selected' : '' }}>Aberta</option>
                            <option value="Em andamento" {{ old('status', $ocorrencia->status) == 'Em andamento' ? 'selected' : '' }}>Em andamento</option>
                            <option value="Resolvida" {{ old('status', $ocorrencia->status) == 'Resolvida' ? 'selected' : '' }}>Resolvida</option>
                            <option value="Excluir" {{ old('status', $ocorrencia->status) == 'Excluir' ? 'selected' : '' }}>Excluir</option>
                        </select>

                        {{-- Tipo --}}
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select name="tipo" class="form-control" required>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('tipo', $ocorrencia->tipo) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

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
                       
                            <div class="mb-3">
                                <label for="localizacao" class="form-label">Localização</label>
                                <input type="text" name="localizacao" id="localizacao" class="form-control" value="{{ old('localizacao', $ocorrencia->localizacao) }}">
                            </div>
                        

                        {{-- Exibindo a imagem atual (se houver) --}}
                        @if ($ocorrencia->imagem)
                            <div class="mb-3">
                                <label class="form-label">Imagem atual</label>
                                <div>
                                    <img src="{{ asset('storage/' . $ocorrencia->imagem) }}" class="img-fluid" alt="Imagem da ocorrência">
                                </div>
                            </div>
                        @endif

                        {{-- Imagem (se houver) --}}
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem (opcional)</label>
                            <input type="file" name="imagem" id="imagem" class="form-control">
                        </div>

                        {{-- Link (se aplicável) --}}
                        <div class="mb-3">
                            <label for="link" class="form-label">Link (opcional)</label>
                            <input type="text" name="link" id="link" class="form-control" value="{{ old('link', $ocorrencia->link ? $ocorrencia->link->url : '') }}">
                        </div>

                        {{-- Botão de Submissão --}}
                        <button type="submit" id="submitBtn" class="btn btn-success w-100">Salvar Alterações</button>
                    </form>
                @else
                    <p class="text-danger">Você não tem permissão para editar este conteúdo.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .exibir_conteudo {
        padding-top: 8rem;
        padding-bottom: 8rem;
    }

    @media (max-width: 767px) {
        .exibir_conteudo {
            padding-top: 6rem;
            padding-bottom: 6rem;
        }

        .card {
            padding: 1.5rem;
        }

        h2, h3 {
            font-size: 1.25rem;
        }

        .btn {
            font-size: 0.875rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }
    }

    .card, .border {
        border-radius: 8px;
    }

    .shadow-sm {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('editOcorrenciaForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Previne o envio normal do formulário

    // Desabilita o botão de envio
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Enviando...';

    const formData = new FormData(this); // Pega os dados do formulário

    // Envia o formulário via fetch (AJAX)
    fetch("{{ route('ocorrencias.update', $ocorrencia->id) }}", {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Exibe o sucesso usando SweetAlert2
            Swal.fire({
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = data.redirect; // Redireciona após o sucesso
            });
        } else {
            // Exibe o erro usando SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: data.errors ? Object.values(data.errors).join(', ') : data.message
            }).then(() => {
                submitBtn.disabled = false; // Reabilita o botão
                submitBtn.innerHTML = 'Salvar Alterações';
            });
        }
    })
    .catch(error => {
        // Caso ocorra um erro
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Algo deu errado, tente novamente mais tarde.'
        }).then(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Salvar Alterações';
        });
    });
});
</script>
@endsection
