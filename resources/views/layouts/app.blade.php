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

    <!-- Manifesto -->
    <link rel="manifest" href="/manifest.json">
</head>

<body>
    <div class="">
        @include('layouts.nav')
        @yield('content')
    </div>

    <!-- Registrar o Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('Service Worker registrado:', reg.scope))
                    .catch(err => console.log('Erro no registro do Service Worker:', err));
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
            installButton.style.backgroundColor = '#618CC7';
            installButton.style.color = '#ffffff';
            installButton.style.border = 'none';
            installButton.style.borderRadius = '8px';
            installButton.style.cursor = 'pointer';
            installButton.style.fontSize = '16px';

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
</body>
</html>
