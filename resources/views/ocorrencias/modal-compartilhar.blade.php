<script>
    let ocorrencia = {
        imagem: @json($ocorrencia->imagem),
        categoria: @json($ocorrencia->categoria->nome),
        titulo: @json($ocorrencia->titulo),
        descricao: @json(Str::limit($ocorrencia->descricao, 528)),
        localizacao: @json($ocorrencia->localizacao),
        created_at: @json(optional($ocorrencia->created_at)->format('d/m/Y'))
    };
</script>

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
                <img id="imagemGeradaModal" src="" alt="Imagem Gerada" class="img-fluid mb-3"
                    style="max-height: 500px; max-width: 100%;">

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

@if ($ocorrencia->categoria->nome == 'Ajuda')
    <style>
        .card-body {
            background: #FFB60A;
            color: rgb(27, 27, 27);
            padding: 20px;
        }
    </style>
@endif

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
        height: 1800px; /* Aumente a altura do container */
        background: linear-gradient(to bottom, #0D6EFD, #90b2df);
        display: flex;
        flex-direction: column;
        justify-content: flex-start; /* Alinha o conteúdo no topo */
        align-items: center;
        text-align: center;
        color: white;
        font-family: 'Arial', sans-serif;
        border-radius: 0px;
    }

    .print-mode h2 {
        font-size: 50px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 2rem;
        background: orange;
        margin: 2rem;
        border-radius: 1rem;
    }

    .print-mode p {
        font-size: 45px;
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
        padding: 0.7rem 0.3rem;
    }

    .card-header img {
        width: 100%; /* A imagem vai ocupar toda a largura da div */
        height: 50%; /* Ajuste a altura da imagem para não ocupar muito espaço */
        object-fit: cover; /* A imagem vai cobrir a área sem distorcer */
    }

    @media (min-width: 768px) {
        .exibir_conteudo {
            padding: 5.7rem 0rem;
        }
    }
</style>

<script>
    document.getElementById('btnCompartilhar').addEventListener('click', function() {
        let comentarios = document.querySelector('.comentarios-secao');
        let btnCompartilharContainer = document.getElementById('btnCompartilharContainer');
        let btnEditarOcorrencia = document.getElementById('btnEditarOcorrencia');

        // Ocultar elementos antes do print
        if (comentarios) comentarios.style.display = 'none';
        if (btnCompartilharContainer) btnCompartilharContainer.style.display = 'none';
        if (btnEditarOcorrencia) btnEditarOcorrencia.style.display = 'none';

        // Criar um container temporário para o print com o conteúdo completo do card
        let printDiv = document.createElement('div');
        printDiv.style.width = "1080px";
        printDiv.style.height = "1800px"; /* Ajuste a altura para mais espaço */
        printDiv.style.display = "flex";
        printDiv.style.flexDirection = "column";
        printDiv.style.justifyContent = "flex-start"; /* Ajuste o conteúdo para começar no topo */
        printDiv.style.alignItems = "stretch";
        printDiv.style.backgroundColor = "#0056b3";
        printDiv.style.color = "white";
        printDiv.style.padding = "0";

        // Definir a imagem conforme a ocorrência
        let imagemSrc = "";
        if (ocorrencia.imagem) {
            imagemSrc = `/storage/${ocorrencia.imagem}`;
        } else if (ocorrencia.categoria === "Ajuda") {
            imagemSrc = `/images/ajuda.png`;
        } else {
            imagemSrc = `/images/default.png`;
        }

        // Criar o conteúdo do card com formato ajustado para story
        printDiv.innerHTML = `
        <div class="card-custom" style="width: 100%; height: 100%; display: flex; flex-direction: column;">
           <div class="card-header" style="position: relative; flex-shrink: 0; height: 50%; overflow: hidden;">
                <img src="${imagemSrc}" alt="Ocorrência" style="width: 100%; height: 100%; object-fit: cover;">
                <div class="title-banner" style="position: absolute; bottom: 30px; left: 20px; background: rgba(0, 0, 0, 0.7); color: white; padding: 12px 16px; font-weight: bold; border-radius: 10px; font-size: 2.6rem; z-index: 1000">
                    ${ocorrencia.categoria === "Vagas de emprego" ? "Vaga" : ocorrencia.categoria} : ${ocorrencia.titulo}
                </div>
            </div>

            <div class="card-body" style="padding: 40px; text-align: left; flex-grow: 1; z-index: 1">
                <p style="font-size: 35px; font-weight: bold;">Publicado em</p>
                <p style="font-size: 35px; font-weight: bold;">${ocorrencia.created_at ? ocorrencia.created_at : "Data não disponível"}</p>
                <p style="font-size: 33px; line-height: 1.6;font-weight: 500">${ocorrencia.descricao}</p>
                <p style="font-size: 35px; font-weight: bold; margin-top: 20px;">Localização: ${ocorrencia.localizacao}</p>
            </div>
            <div class="card-footer" style="text-align: center; font-weight: bold; font-size: 30px; padding: 20px; background:#0056b3; color: #fff;">
                uairesolve.com.br
            </div>
        </div>
    `;

        document.body.appendChild(printDiv);

        html2canvas(printDiv, {
            scale: 2
        }).then(canvas => {
            let imgData = canvas.toDataURL('image/png');
            document.body.removeChild(printDiv);

            if (comentarios) comentarios.style.display = 'block';
            if (btnCompartilharContainer) btnCompartilharContainer.style.display = 'flex';
            if (btnEditarOcorrencia) btnEditarOcorrencia.style.display = 'flex';

            let imgModal = document.getElementById('imagemGeradaModal');
            imgModal.src = imgData;

            let modal = new bootstrap.Modal(document.getElementById('modalCompartilhar'));
            modal.show();

            document.getElementById('btnDownloadImagem').addEventListener('click', function() {
                let link = document.createElement('a');
                link.href = imgData;
                link.download = `${ocorrencia.titulo}.png`;
                link.click();
            });
        });
    });
</script>
