<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalLabel">Criar Postagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário para criação de postagem -->
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

                    <button type="submit" class="btn btn-primary w-100">Publicar</button>
                </form>
            </div>
        </div>
    </div>
</div>
