@extends('layouts.app')

@section('content')
@auth
<div class="container mt-4">
    <h2 class="text-center mb-4">Registrar Nova Ocorrência</h2>

    <form action="{{ route('ocorrencias.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" id="titulo" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea id="descricao" name="descricao" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="localizacao" class="form-label">Localização</label>
            <input type="text" id="localizacao" name="localizacao" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem (opcional)</label>
            <input type="file" id="imagem" name="imagem" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Registrar Ocorrência</button>
    </form>
</div>
@else
<div class="alert alert-warning text-center mt-4 min-vh-100">
    Você precisa estar logado para registrar uma ocorrência. <br>
    <a href="{{ route('login') }}" class="btn btn-primary mt-2">Fazer Login</a>
</div>
@endauth
@endsection
