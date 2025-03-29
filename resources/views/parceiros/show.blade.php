@extends('layouts.app')

@section('content')
<div class="container pg_perfil mt-4">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <!-- Foto de perfil -->
            <img src="{{ asset('storage/' . $parceiro->logo) }}" class="rounded-circle img-thumbnail profile-img" alt="{{ $parceiro->nome }}">

            <!-- Nome do parceiro -->
            <h2 class="mt-3">{{ $parceiro->nome }}</h2>
            
            <hr>
             
            <!-- Descrição -->
            <p class="text-muted">{{ Str::limit($parceiro->descricao, 200) }}</p>

            <!-- Verificar se o usuário logado é o autor do parceiro -->
            @if(auth()->check() && auth()->user()->id === $parceiro->user_id)
                <!-- Texto pequeno com ícone de edição -->
                <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil-square"></i> Editar Perfil
                </a>
                <br>
                <button type="button" class="btn btn-outline-primary mt-3" data-bs-toggle="modal" data-bs-target="#postModal">
                    Adicionar Postagem
                </button>
            @endif
        </div>
    </div>

    <!-- Se houver postagens, exiba-as -->
    <div class="mt-4">
        <h4>Postagens</h4>
        @if($parceiro->posts->count() > 0)
            <ul class="list-group">
                @foreach($parceiro->posts as $postagem)
                    <div class="card mb-4">
                        <div class="card-header text-center">
                            @if($postagem->imagem)
                                <img src="{{ asset('storage/' . $postagem->imagem) }}" class="card-img-top img_post" alt="Postagem">
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="d-flex justify-content-between align-items-center">
                                {{ $postagem->titulo }}
                                @if(auth()->check() && auth()->user()->id === $parceiro->user_id)
                                    <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#editPostModal{{ $postagem->id }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </h5>
                            <p>{!! Str::limit($postagem->conteudo, 300) !!}</p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="https://link-da-doacao.com" class="btn btn-donate fixed-donate-btn text-white" target="_blank">
                                <i class="fas fa-donate"></i> Faça uma Doação
                            </a>
                        </div>
                    </div>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Não há postagens ainda.</p>
        @endif
        @if(!empty($instagramPosts))
    <h4 class="mt-4">Instagram de {{ $parceiro->nome }}</h4>
    <div class="instagram-container">
        @foreach($instagramPosts as $post)
            <blockquote class="instagram-media" data-instgrm-permalink="{{ $post }}" data-instgrm-version="14"></blockquote>
        @endforeach
    </div>
    <script async src="//www.instagram.com/embed.js"></script>
@endif

        
    </div>

    <!-- Modals -->
    @include('parceiros.componentes.modal.editar-perfil')
    @include('parceiros.componentes.modal.adicionar-postagem')
    @include('parceiros.componentes.modal.editar-postagem')

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>

<style>
    /* Estilos Modernos */
    .profile-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
    }

    .btn-primary:hover, .btn-outline-primary:hover {
        background-color: #4a86e8;
        border-color: #4a86e8;
    }

    .btn-donate {
        background-color: #1C6EFD;
        transition: background-color 0.3s ease;
    }

    .btn-donate:hover {
        background-color: #155b9b;
    }

    .img_post {
        width: 100%;
        max-width: 80%;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Adiciona margem inferior para evitar sobreposição */
    .pg_perfil {
        margin-bottom: 100px;
    }

    /* Responsividade para telas maiores que 900px */
    @media (min-width: 900px) {
        .pg_perfil {
            width: 50vw;
        }
    }

    /* Animação de suavização nos cards */
    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Ajustes de modal e botão */
    .modal-header, .modal-footer {
        border: none;
    }

    .modal-content {
        border-radius: 8px;
    }
</style>
<style>
    .instagram-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }

    .instagram-media {
        max-width: 320px; /* Ajuste conforme necessário */
        width: 100%;
    }
</style>

@endsection
