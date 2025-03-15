@auth
    <form action="{{ route('ocorrencias.like', $ocorrencia->id) }}" method="POST" id="like-form" style="display:inline;">
        @csrf
        @if ($ocorrencia->isLikedByUser(auth()->user()->id))
            {{-- Botão de Descurtir --}}
            <button type="button" class="btn btn-danger btn-sm" id="like-button">
                <i class="fas fa-heart"></i> <span id="total-likes">{{ $totalLikes }}</span> Descurtir
            </button>
        @else
            {{-- Botão de Curtir --}}
            <button type="button" class="btn btn-outline-danger btn-sm" id="like-button">
                <i class="fas fa-heart"></i> <span id="total-likes">{{ $totalLikes }}</span> Curtir
            </button>
        @endif
    </form>
@endauth

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Quando o usuário clicar no botão de "Curtir" ou "Descurtir"
        document.getElementById('like-button').addEventListener('click', function() {
            var form = this.closest('form'); // Encontre o formulário mais próximo
            var url = form.getAttribute('action'); // Obtém a URL da ação
            var csrfToken = form.querySelector('input[name="_token"]').value; // Obtém o token CSRF
            var button = this; // O botão que foi clicado
            var totalLikesElement = document.getElementById('total-likes'); // Elemento que exibe o total de likes

            // Preparar os dados para enviar via Fetch
            var formData = new FormData();
            formData.append('_token', csrfToken);

            // Enviar a requisição usando Fetch
            fetch(url, {
                method: 'POST',
                body: formData, // Dados do formulário
                headers: {
                    'Accept': 'application/json', // Informar que a resposta será em JSON
                },
            })
            .then(response => response.json()) // Espera pela resposta JSON
            .then(data => {
                if (data.message) {
                    // Atualiza o total de likes no botão
                    var currentLikes = parseInt(totalLikesElement.textContent);

                    // Incrementa ou decrementa o total de likes
                    if (button.classList.contains('btn-outline-danger')) {
                        button.classList.remove('btn-outline-danger');
                        button.classList.add('btn-danger');
                        totalLikesElement.textContent = currentLikes + 1; // Incrementa
                        button.innerHTML = '<i class="fas fa-heart"></i> <span id="total-likes">' + (currentLikes + 1) + '</span> Descurtir';
                    } else {
                        button.classList.remove('btn-danger');
                        button.classList.add('btn-outline-danger');
                        totalLikesElement.textContent = currentLikes - 1; // Decrementa
                        button.innerHTML = '<i class="fas fa-heart"></i> <span id="total-likes">' + (currentLikes - 1) + '</span> Curtir';
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao curtir/descurtir a ocorrência:', error);
                alert('Ocorreu um erro. Tente novamente.');
            });
        });
    });
</script>
