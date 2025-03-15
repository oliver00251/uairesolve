 {{-- Modal de Visualização da Imagem e Download --}}
 <div class="modal fade" id="modalCompartilhar" tabindex="-1" aria-labelledby="modalCompartilharLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCompartilharLabel">Visualização da Ocorrência</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Imagem gerada será exibida aqui -->
                <img id="imagemGeradaModal" src="" alt="Imagem Gerada" class="img-fluid mb-3" style="max-height: 300px; max-width: 100%;">

                <!-- Botão de download da imagem -->
                <div class="mt-3">
                    <button id="btnDownloadImagem" class="btn btn-success">
                        <i class="fas fa-download"></i> Baixar Imagem
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS para formatação no print --}}
<style>
    .ocorrencia-img {
        width: 85%;
        max-width: 900px;
        object-fit: cover;
        border-radius: 15px;
        border: 8px solid white;
        margin-top: 20px;
    }

    /* Estilos aplicados SOMENTE no print */
    .print-mode {
        width: 1080px;
        height: 1920px;
        background: linear-gradient(to bottom, #0D6EFD, #90b2df);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        font-family: 'Arial', sans-serif;
        border-radius: 0px;
    }

    .print-mode h2 {
        font-size: 85px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 2rem;
        background: orange;
        margin: 4rem;
        border-radius: 1rem;
    }

    .print-mode p {
        font-size: 58px;
        max-width: 84%;
        margin-bottom: 30px;
        font-weight: 500;
        text-align: left;
    }

    .print-mode .ocorrencia-img {
        width: 90%;
        max-width: 900px;
        height: auto;
    }

    .exibir_conteudo {
        padding: 5.7rem 0.3rem;
    }

    @media (min-width: 768px) {
        .exibir_conteudo {
            padding: 5.7rem 0rem;
        }
    }
</style>

{{-- JavaScript para Capturar e Exibir a Imagem com Botão de Download --}}
<script>
    document.getElementById('btnCompartilhar').addEventListener('click', function() {
        let comentarios = document.querySelector('.comentarios-secao');
        let btnCompartilharContainer = document.getElementById('btnCompartilharContainer');
        let btnEditarOcorrencia = document.getElementById('btnEditarOcorrencia');

        // Ocultar elementos antes do print
        if (comentarios) comentarios.style.display = 'none';
        if (btnCompartilharContainer) btnCompartilharContainer.style.display = 'none';
        if (btnEditarOcorrencia) btnEditarOcorrencia.style.display = 'none';

        // Criar um container temporário para o print
        let printDiv = document.createElement('div');
        printDiv.classList.add('print-mode');

        // Criar título
        let title = document.createElement('h2');
        title.innerText = "📢 Atenção";

        // Limitar a descrição às 14 primeiras palavras e adicionar a sugestão
        let descricao = "{{ $ocorrencia->descricao }}";
        let palavras = descricao.split(' '); // Divide a descrição em palavras
        let descricaoLimitada = palavras.slice(0, 14).join(' ') + (palavras.length > 14 ? '... Veja mais em uairesolve.com.br' : '');

        // Criar detalhes
        let details = document.createElement('p');
        details.innerHTML = `
            <strong>Título:</strong> {{ $ocorrencia->titulo }} <br>
            <strong>Descrição:</strong> ${descricaoLimitada} <br>
            <strong>Localização:</strong> {{ $ocorrencia->localizacao }} <br>
            <strong>Publicado em:</strong> {{ $ocorrencia->created_at ? $ocorrencia->created_at->format('d/m/Y H:i') : 'Data não disponível' }}
        `;

        // Criar imagem
        let img = document.createElement('img');
        img.src = "{{ asset('storage/' . $ocorrencia->imagem) }}";
        img.classList.add('ocorrencia-img');

        // Adicionar elementos ao printDiv
        printDiv.appendChild(title);
        printDiv.appendChild(details);
        if (img.src) printDiv.appendChild(img);

        // Adicionar ao body e capturar imagem
        document.body.appendChild(printDiv);

        // Agora que a div está no DOM, podemos capturar a imagem
        html2canvas(printDiv, { scale: 2 }).then(canvas => {
            let imgData = canvas.toDataURL('image/png');
            document.body.removeChild(printDiv);

            // Restaurar elementos ocultos
            if (comentarios) comentarios.style.display = 'block';
            if (btnEditarOcorrencia) btnEditarOcorrencia.style.display = 'flex';

            // Exibir o modal com a imagem gerada
            let modal = new bootstrap.Modal(document.getElementById('modalCompartilhar'));
            let imgModal = document.getElementById('imagemGeradaModal');
            imgModal.src = imgData;
            modal.show(); // Exibe o modal

            // Adicionar opção de download da imagem
            document.getElementById('btnDownloadImagem').addEventListener('click', function() {
                let link = document.createElement('a');
                link.href = imgData;
                link.download = 'ocorrencia.png'; // Nome do arquivo de download
                link.click();
            });
        });
    });
</script>