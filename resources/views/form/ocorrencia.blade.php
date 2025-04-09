<h2 class="text-center mb-4">Registrar Nova Ocorrência</h2>

<form id="form-ocorrencia" enctype="multipart/form-data">
    @csrf

    {{-- Categoria --}}
    <div class="mb-3">
        <label for="categoria_id" class="form-label">Categoria</label>
        <select name="categoria_id" class="form-control" required>
            <option value="">Selecione</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
            @endforeach
        </select>
    </div>

    {{-- Título --}}
    <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" required>
    </div>

    {{-- Descrição --}}
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea name="descricao" class="form-control" rows="4" required></textarea>
    </div>

    {{-- Cidade --}}
    <div class="mb-3">
        <label for="cidade_id" class="form-label">Cidade</label>
        <select name="cidade_id" id="cidade_id" class="form-control" required>
            <option value="">Selecione</option>
            @foreach($cidades as $cidade)
                <option value="{{ $cidade->id }}">{{ $cidade->nome }} - {{ $cidade->estado }}</option>
            @endforeach
        </select>
    </div>

    {{-- Bairro (oculto por enquanto) --}}
    <div class="mb-3" style="display: none;">
        <label for="bairro_id" class="form-label">Bairro</label>
        <select name="bairro_id" id="bairro_id" class="form-control">
            <option value="1" selected>Centro</option>
        </select>
    </div>

    {{-- Localização --}}
    <div class="mb-3">
        <label for="localizacao" class="form-label">Endereço (rua, ponto de referência...)</label>
        <input type="text" name="localizacao" class="form-control" required>
    </div>

    {{-- Latitude / Longitude (hidden) --}}
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">

    {{-- Link (condicional) --}}
    <div class="mb-3" id="link-container" style="display: none;" hidden>
        <label for="link" class="form-label">Link (opcional)</label>
        <input type="text" name="link" class="form-control" placeholder="https://...">
    </div>

    {{-- Imagem --}}
    <div class="mb-3">
        <label for="imagem" class="form-label">Imagem</label>
        <input type="file" name="imagem" class="form-control">
    </div>

    {{-- Status --}}
    <input type="hidden" name="status" value="Aberta">

    <div id="status-message" class="alert alert-info" style="display: none;">Enviando...</div>
    <button type="submit" class="btn btn-primary">Registrar</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.9/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const cidadeSelect = document.getElementById('cidade_id');
    const bairroSelect = document.getElementById('bairro_id');
    const categoriaSelect = document.querySelector('select[name="categoria_id"]');
    const linkContainer = document.getElementById('link-container');

    // Geolocalização automática
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                latitudeInput.value = position.coords.latitude;
                longitudeInput.value = position.coords.longitude;
            },
            error => {
                console.warn("Geolocalização não permitida ou falhou.");
                latitudeInput.value = "";
                longitudeInput.value = "";
            }
        );
    }

    // Mostrar/ocultar campo de link
    categoriaSelect.addEventListener('change', function () {
        const nome = this.options[this.selectedIndex].text;
        if (['Ajuda', 'Causa Animal', 'Eventos', 'Vagas de emprego'].includes(nome)) {
            linkContainer.style.display = 'block';
        } else {
            linkContainer.style.display = 'none';
        }
    });

    // Carregar bairros via AJAX
    cidadeSelect.addEventListener('change', function () {
        const cidadeId = this.value;
        bairroSelect.innerHTML = '<option value="">Carregando...</option>';

        fetch(`/api/cidades/${cidadeId}/bairros`)
            .then(res => res.json())
            .then(data => {
                bairroSelect.innerHTML = '<option value="">Selecione</option>';
                data.forEach(bairro => {
                    const option = document.createElement('option');
                    option.value = bairro.id;
                    option.textContent = bairro.nome;
                    bairroSelect.appendChild(option);
                });
            })
            .catch(() => {
                bairroSelect.innerHTML = '<option value="">Erro ao carregar bairros</option>';
            });
    });

    // Submissão do formulário
    document.getElementById('form-ocorrencia').addEventListener('submit', function (e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        document.getElementById('status-message').style.display = 'block';

        const formData = new FormData(this);
        fetch("{{ route('ocorrencias.store') }}", {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Sucesso!', 'Ocorrência registrada.', 'success').then(() => {
                    window.location.href = "{{ route('ocorrencias.index') }}";
                });
            } else {
                Swal.fire('Erro!', data.message || 'Erro ao registrar.', 'error');
            }
        })
        .catch(() => {
            Swal.fire('Erro!', 'Algo deu errado. Tente novamente.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            document.getElementById('status-message').style.display = 'none';
        });
    });
});
</script>
