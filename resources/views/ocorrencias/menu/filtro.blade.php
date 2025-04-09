<form method="GET" action="{{ route('ocorrencias.index') }}">
    <!-- Botões de categoria -->
    <div class="scrolling-wrapper mb-3">
        <a href="{{ route('ocorrencias.index', ['status' => request('status')]) }}"
           class="btn btn-sm filtro-btn {{ request('categoria') ? 'blue_theme' : 'btn-success' }}">
            Tudo
        </a>

        @foreach($categorias as $categoria)
            <a href="{{ route('ocorrencias.index', ['categoria' => $categoria->id, 'status' => request('status')]) }}"
               class="btn btn-sm filtro-btn {{ request('categoria') == $categoria->id ? 'btn-success' : 'blue_theme' }}">
                {{ $categoria->nome }}
            </a>
        @endforeach
    </div>

    <!-- Filtro de status -->
    <div class="mb-2">
        <label class="form-label">Status:</label>
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Todos</option>
            @foreach($statusList as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
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
    scrollbar-width: none;
    justify-content: center;
}
.scrolling-wrapper::-webkit-scrollbar {
    display: none;
}
@media (max-width: 768px) {
    .scrolling-wrapper {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 5px;
        scrollbar-width: none;
        justify-content: flex-start !important;
    }

    .scrolling-wrapper::-webkit-scrollbar {
        display: none;
    }
}

.filtro-btn {
    border-radius: 20px;
    padding: 5px 15px;
    text-decoration: none;
    color: white;
    flex-shrink: 0;
}
.filtro-btn:hover {
    opacity: 0.5;
}
.blue_theme {
    background: #0D6EFD !important;
}
</style>
