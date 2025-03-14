<h2 class="text-center mb-4">Registrar Nova Ocorrência</h2>

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

    <div class="mb-3">
        <label for="localizacao" class="form-label">Localização</label>
        <input type="text" id="localizacao" name="localizacao" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="imagem" class="form-label">Imagem (opcional)</label>
        <input type="file" id="imagem" name="imagem" class="form-control">
    </div>
    @include('form.localizacao')


    <button type="submit" class="btn btn-primary">Registrar Ocorrência</button>
</form>
<style>
    @media (max-width: 767px) {
    .alert {
        padding-top: 12rem !important;
    }

    .registro_new {
    padding: 3rem !important; /* Desktop */
}

/* Estilos para telas menores que 768px (Mobile) */
@media (max-width: 768px) {
    .registro_new  {
        padding: 3rem; /* Ajuste menor para mobile */
    }
}

}

</style>