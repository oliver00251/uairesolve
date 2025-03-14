<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UaiResolve</title>

    <!-- Link do Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Chart.js CSS (não é necessário, pois Chart.js é uma biblioteca JS) -->
</head>
<body>
    <div class="">
      @include('layouts.nav')
        
        @yield('content') <!-- Aqui vai o conteúdo das páginas -->
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
            installButton.style.left = '20px';
            installButton.style.zIndex = '1000';
            installButton.style.padding = '10px 20px';
            installButton.style.backgroundColor = '#343a40';
            installButton.style.color = '#ffffff';
            installButton.style.border = 'none';
            installButton.style.borderRadius = '5px';
            installButton.style.cursor = 'pointer';

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

            document.body.appendChild(installButton);
        }
    </script>
</body>
</html>
