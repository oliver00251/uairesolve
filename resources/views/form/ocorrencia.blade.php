<h2 class="text-center mb-4">Registrar Nova Publicação</h2>

<form id="form-ocorrencia" enctype="multipart/form-data">
    @csrf
    <select name="tipo" class="form-control" required>
        @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}" {{ old('tipo') == $categoria->id ? 'selected' : '' }}>
                {{ $categoria->nome }}
            </option>
        @endforeach
    </select>

    <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" id="titulo" name="titulo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea id="descricao" name="descricao" class="form-control" rows="3" required></textarea>
    </div>

    <div class="mb-3" id="link-container" style="display: none;">
        <label for="link" class="form-label">Link (opcional)</label>
        <input type="text" id="link" name="link" class="form-control" placeholder="Digite o link">
    </div>

    <div class="mb-3">
        <label for="localizacao" class="form-label">Localização</label>
        <input type="text" id="localizacao" name="localizacao" placeholder="Digite o nome da cidade ou endereço completo " class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="imagem" class="form-label">Imagem (opcional)</label>
        <input type="file" id="imagem" name="imagem" class="form-control">
    </div>

    @include('form.localizacao')

    <div id="status-message" style="display: none; margin-top: 10px;" class="alert alert-info">
        Enviando, por favor aguarde...
    </div>

    <button type="submit" id="submit-button" class="btn btn-primary">Registrar</button>
</form>

<style>
    #status-message {
        display: none;
        margin-top: 10px;
    }

    .registro_new {
        padding: 3rem !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.9/dist/sweetalert2.all.min.js"></script>
<script>
    document.getElementById('form-ocorrencia').addEventListener('submit', function (e) {
        e.preventDefault();

        const submitButton = document.getElementById('submit-button');
        const statusMessage = document.getElementById('status-message');
        const formData = new FormData(this);

        // Validação de tamanho da imagem no front-end (limite de 20MB)
        const fileInput = document.getElementById('imagem');
        if (fileInput.files.length > 0) {
            const fileSize = fileInput.files[0].size / 1024 / 1024; // em MB
            if (fileSize > 20) {
                Swal.fire({
                    title: 'Erro!',
                    text: 'O tamanho da imagem não pode exceder 20MB.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                return;
            }
        }

        // Desabilitar o botão de envio e exibir a mensagem de status
        submitButton.disabled = true;
        statusMessage.style.display = 'block';

        // Exibir um alerta de "Enviando" enquanto o envio está em progresso
        Swal.fire({
            title: 'Enviando...',
            text: 'Por favor, aguarde enquanto registramos sua ocorrência.',
            icon: 'info',
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Envio do formulário via fetch
        fetch("{{ route('ocorrencias.store') }}", {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Ocorrência registrada com sucesso!',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    window.location.href = "{{ route('ocorrencias.index') }}"; // Redireciona para a lista de ocorrências
                });
            } else {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao registrar ocorrência. Tente novamente.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Erro!',
                text: 'Ocorreu um erro. Por favor, tente novamente.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        })
        .finally(() => {
            // Reabilita o botão de envio e oculta a mensagem de status
            submitButton.disabled = false;
            statusMessage.style.display = 'none';
        });
    });

    // Função para mostrar ou esconder o campo de link
    function toggleLinkField() {
        const categoriaSelect = document.querySelector('select[name="tipo"]');
        const linkContainer = document.getElementById('link-container');
        const selectedCategoria = categoriaSelect.options[categoriaSelect.selectedIndex].text; // Obtém o nome da categoria selecionada

        // Se a categoria selecionada for 'Ajuda', 'Causa Animal' ou 'Eventos', mostrar o campo de link
        if (['Ajuda', 'Causa Animal', 'Eventos', 'Vagas de emprego'].includes(selectedCategoria)) {
            linkContainer.style.display = 'block';
        } else {
            linkContainer.style.display = 'none';
        }
    }

    // Chama a função ao carregar a página e quando a categoria for alterada
    document.addEventListener('DOMContentLoaded', toggleLinkField);
    document.querySelector('select[name="tipo"]').addEventListener('change', toggleLinkField);
</script>
