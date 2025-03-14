<h2 class="text-center mb-4">Registrar Nova Sugestão</h2>

<form action="{{ route('ocorrencias.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" value="{{$registro}}" name="tipo" hidden>
    <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" id="titulo" name="titulo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea id="descricao" name="descricao" class="form-control" rows="3" required></textarea>
    </div>
    @include('form.localizacao')
    <button type="submit" class="btn btn-primary">Registrar Sugestão</button>
</form>