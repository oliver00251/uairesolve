<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Editar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body min-vh-100">
                <!-- Formulário para editar perfil -->
                <form action="{{ route('parceiros.update', $parceiro->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" class="form-control" id="nome" value="{{ old('nome', $parceiro->nome) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea name="descricao" class="form-control" id="descricao" rows="5" required>{{ old('descricao', $parceiro->descricao) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="logo">Logo (opcional)</label>
                        <input type="file" name="logo" class="form-control-file" id="logo">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>
