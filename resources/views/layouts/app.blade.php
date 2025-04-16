<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UaiResolve - Nossa cidade, nosso compromisso</title>
    <meta name="description" content="Juntos, podemos melhorar nossa cidade! Registre ocorrências e colabore para um ambiente melhor.">

    <!-- Open Graph -->
    <meta property="og:title" content="UaiResolve - Nossa cidade, nosso compromisso">
    <meta property="og:description" content="Juntos, podemos melhorar nossa cidade! Registre ocorrências e colabore para um ambiente melhor.">
    <meta property="og:image" content="{{ asset('images/preview.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="UaiResolve - Nossa cidade, nosso compromisso">
    <meta name="twitter:description" content="Juntos, podemos melhorar nossa cidade! Registre ocorrências e colabore para um ambiente melhor.">
    <meta name="twitter:image" content="{{ asset('images/preview.png') }}">

    <!-- Link do Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Manifesto -->
    <link rel="manifest" href="/manifest.json">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
     
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.9/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.9/dist/sweetalert2.all.min.js"></script>

</head>

<body>
    @include('layouts.nav')
    <div style="margin-top:10vh !important">
    
        
        @yield('content') <!-- Aqui vai o conteúdo das páginas -->
        <style>
    nav{
    border-radius: 0rem 0rem 1.5rem 1.5rem;
}
</style>
    </div>

    <!-- jQuery (necessário para o DataTables e Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.jsdelivr.net/npm/datatables.net/js/jquery.dataTables.min.js"></script>

    <!-- Chart.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('scripts') <!-- Scripts específicos da página -->
    
    <!-- Registrar o Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                console.log('Service Worker registrado com sucesso:', registration.scope);
            }).catch(function(error) {
                console.log('Falha ao registrar o Service Worker:', error);
            });
        }
    </script>

    <!-- Mostrar o prompt de instalação do PWA -->
    <script>
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            showInstallPromotion();
        });

        function showInstallPromotion() {
            const installButton = document.createElement('button');
            installButton.innerText = 'Instale nosso app';
            installButton.style.position = 'fixed';
            installButton.style.bottom = '20px';
            installButton.style.right = '20px';
            installButton.style.zIndex = '1000';
            installButton.style.padding = '12px 20px';
            installButton.style.backgroundColor = '#618CC7';  // Cor azul da sua paleta
            installButton.style.color = '#ffffff';
            installButton.style.border = 'none';
            installButton.style.borderRadius = '8px';
            installButton.style.cursor = 'pointer';
            installButton.style.fontSize = '16px';

            // Adicionar o botão à tela
            document.body.appendChild(installButton);

            installButton.addEventListener('click', () => {
                installButton.style.display = 'none';
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('Usuário aceitou o prompt de instalação');
                    } else {
                        console.log('Usuário rejeitou o prompt de instalação');
                    }
                    deferredPrompt = null;
                });
            });
        }
    </script>
    <style>
        .bg-info{
            background: #0D6EFD !important;
        }
    </style>
</body>
</html>
