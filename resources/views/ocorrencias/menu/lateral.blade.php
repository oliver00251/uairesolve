<div class="col-lg-3 d-none d-lg-block">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Filtrar por Categoria</h5>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <a href="{{ route('ocorrencias.index') }}" class="text-decoration-none">Todas</a>
            </li>
            @foreach($categorias as $categoria)
                <li class="list-group-item">
                    <a href="{{ route('ocorrencias.index', ['categoria' => $categoria->id]) }}" class="text-decoration-none">
                        {{ $categoria->nome }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>