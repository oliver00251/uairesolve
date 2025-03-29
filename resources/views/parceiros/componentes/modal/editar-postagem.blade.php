@foreach($parceiro->posts as $postagem)
    <div class="modal fade" id="editPostModal{{ $postagem->id }}" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel{{ $postagem->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPostModalLabel{{ $postagem->id }}">Editar Postagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulário para edição de postagem -->
                    <form action="{{ route('posts.update', $postagem->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" name="titulo" class="form-control" value="{{ $postagem->titulo }}" required>
                        </div>

                        <div class="form-group">
                            <label for="conteudo">Conteúdo</label>
                            <textarea name="conteudo" class="form-control" rows="5" required>{{ $postagem->conteudo }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="imagem">Imagem (opcional)</label>
                            <input type="file" name="imagem" class="form-control-file" id="imagem">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach