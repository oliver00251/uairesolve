<div class="d-lg-none mb-3">
  
    <!-- Filtro com rolagem horizontal -->
    <div class="scrolling-wrapper">
        <a href="{{ route('ocorrencias.index') }}" class="btn btn-sm filtro-btn {{ request('categoria') ? 'blue_theme' : 'btn-success' }}">Tudo</a>
        @foreach($categorias as $categoria)
            <a href="{{ route('ocorrencias.index', ['categoria' => $categoria->id]) }}" 
               class="btn btn-sm filtro-btn {{ request('categoria') == $categoria->id ? 'btn-success' : 'blue_theme' }}">
                {{ $categoria->nome }}
            </a>
        @endforeach
    </div>
</div>

<style>
/* Faz o menu rolar horizontalmente */
.scrolling-wrapper {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 5px;
    scrollbar-width: none; /* Esconde a barra de rolagem no Firefox */
}

/* Esconde a barra de rolagem no Chrome e Safari */
.scrolling-wrapper::-webkit-scrollbar {
    display: none;
}

.filtro-btn {
    border-radius: 20px;
    padding: 5px 15px;
    text-decoration: none;
    color: white;
    flex-shrink: 0; /* Evita que os botões encolham */
}

.filtro-btn:hover {
    opacity: 0.5;
}

.blue_theme {
    background: #0D6EFD !important;
}

</style>
