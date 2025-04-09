<form method="GET" action="{{ route('ocorrencias.index') }}">
    <div class="mb-3">
        <!-- Filtro de Categoria com rolagem -->
        <div class="scrolling-wrapper">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <a href="{{ route('ocorrencias.index', ['status' => request('status')]) }}" class="btn btn-sm filtro-btn {{ request('categoria') ? 'blue_theme' : 'btn-success' }}">Tudo</a>
            @foreach($categorias as $categoria)
                <button type="submit" name="categoria" value="{{ $categoria->id }}"
                        class="btn btn-sm filtro-btn {{ request('categoria') == $categoria->id ? 'btn-success' : 'blue_theme' }}">
                    {{ $categoria->nome }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="mb-2">
        <label class="form-label">Status:</label>
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Todos</option>
            @foreach($statusList as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select>
        <input type="hidden" name="categoria" value="{{ request('categoria') }}">
    </div>
</form>


<style>
/* Faz o menu rolar horizontalmente */
.scrolling-wrapper {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 5px;
    scrollbar-width: none; /* Esconde a barra de rolagem no Firefox */
    justify-content: center;
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
