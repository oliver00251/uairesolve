@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Criar Parceiro</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('parceiros.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nome -->
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Parceiro</label>
                    <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" placeholder="Digite o nome" required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" rows="4" placeholder="Fale um pouco sobre esse parceiro"></textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Logo -->
                <div class="mb-3">
                    <label for="logo" class="form-label">Logo do Parceiro</label>
                    <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror">
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botão de Envio -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Salvar Parceiro</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
