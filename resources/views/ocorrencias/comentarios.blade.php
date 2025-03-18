<div class="comentarios-secao p-3">
    <h3 class="mb-3">Comentários</h3>

    {{-- Lista de Comentários --}}
    @forelse ($ocorrencia->comentarios as $comentario)
        <div class="mb-3 border p-4 rounded bg-light shadow-sm">
            <p><strong>{{ $comentario->usuario->name ?? 'Usuário Anônimo' }}</strong> comentou:</p>
            <p>{{ $comentario->comentario }}</p>
            <small class="text-muted">
                {{ $comentario->created_at->diffForHumans() }}
                ({{ $comentario->created_at->format('d/m/Y H:i') }})
            </small>
        </div>
    @empty
        <p>Não há comentários ainda.</p>
    @endforelse

    {{-- Formulário para Adicionar Comentário --}}
    @auth
        <div class="comentarios-formulario mb-4">
            <h4>Deixe seu comentário:</h4>
            <form action="{{ route('comentarios.store', $ocorrencia->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="comentario" class="form-control" rows="4" placeholder="Escreva seu comentário..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Comentar</button>
            </form>
        </div>
    @endauth

    {{-- Exibe uma mensagem para quem não estiver logado --}}
    @guest
        <p>Para comentar, você precisa <a href="{{ route('login') }}">fazer login</a>.</p>
    @endguest

    <hr>

   
</div>
