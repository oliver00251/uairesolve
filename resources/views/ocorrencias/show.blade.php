@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 exibir_conteudo">
                {{-- Card de Ocorrência --}}
                <div class="card shadow-sm p-4" id="ocorrenciaCard">
                    {{-- Ícone de voltar e título --}}
                    <div class="d-flex align-items-center mb-4">
                        <a href="{{ route('ocorrencias.index') }}" class="text-decoration-none text-dark">
                            <i class="fas fa-arrow-left fa-lg"></i>
                        </a>
                        <h2 class="ms-2 mb-0 fs-5 fs-md-3">Detalhes da {{ $ocorrencia->categoria->nome ?? 'Outros' }}</h2>
                    </div>

                    {{-- Detalhes da ocorrência --}}
                    <div class="mb-3">
                        <p><strong>Título:</strong> <span class="text-muted">{{ $ocorrencia->titulo }}</span></p>
                        <p><strong>Descrição:</strong> <span class="text-muted">{!! nl2br(e($ocorrencia->descricao)) !!}</span></p>

                        <p class="{{ $ocorrencia->tipo != 'O' ? 'd-none' : '' }}">
                            <strong>Localização:</strong> <span class="text-muted">{{ $ocorrencia->localizacao }}</span>
                        </p>

                        {{-- Link (se existir) --}}
                        @if ($ocorrencia->link)
                            <p>
                                <strong>Link:</strong>
                                <a href="{{ $ocorrencia->link->url }}" target="_blank"
                                    class="text-muted">{{ $ocorrencia->link->url }}</a>
                            </p>
                        @endif

                        <p><strong>Publicado em:</strong> <span class="text-muted">
                                {{ $ocorrencia->created_at ? $ocorrencia->created_at->format('d/m/Y H:i') : 'Data não disponível' }}
                            </span>
                        </p>
                    </div>

                    {{-- Imagem da ocorrência --}}
                    @if ($ocorrencia->imagem)
                        <div class="text-center my-4">
                            <img src="{{ asset('storage/' . $ocorrencia->imagem) }}"
                                class="img-fluid ocorrencia-img shadow">
                        </div>
                    @endif

                    <hr>

                    {{-- Seção de Ações (Editar, Compartilhar, Curtir) --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        {{-- Botão de Editar (Somente para o dono da ocorrência) --}}
                        @if (Auth::check() && Auth::user()->id === $ocorrencia->user_id)
                            <a href="{{ route('ocorrencias.edit', $ocorrencia->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar Ocorrência
                            </a>
                        @endif

                        {{-- Botão de Compartilhar --}}
                        <button id="btnCompartilhar" class="btn btn-secondary btn-sm">
                            <i class="fas fa-share-alt"></i> Compartilhar
                        </button>


                        {{-- Like/Deslike da Ocorrência --}}
                        @include('ocorrencias.btn-curtir')
                    </div>
                    @if (auth()->check() && auth()->user()->tipo == 'admin')
                        <a href="{{ route('ocorrencias.gera.image', ['id' => $ocorrencia->id]) }}" download>
                            Baixar Imagem da postagem
                        </a>
                    @endif


                    <hr>

                    {{-- Seção de Comentários --}}
                    @include('ocorrencias.comentarios')
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Compartilhar --}}
    @include('ocorrencias.modal-compartilhar')
@endsection
