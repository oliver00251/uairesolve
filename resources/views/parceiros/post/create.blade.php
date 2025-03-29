@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2>Criar Postagem</h2>

            <!-- Formulário de criação de postagem -->
            <form action="{{ route('posts.store', $parceiro->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" class="form-control" id="titulo" required>
                </div>

                <div class="form-group">
                    <label for="conteudo">Conteúdo</label>
                    <textarea name="conteudo" class="form-control" id="conteudo" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="imagem">Imagem (opcional)</label>
                    <input type="file" name="imagem" class="form-control-file" id="imagem">
                </div>

                <button type="submit" class="btn btn-primary">Publicar</button>
            </form>
        </div>
    </div>
</div>
@endsection
