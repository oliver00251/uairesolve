const CACHE_NAME = 'uairesolve-cache-v3';
const OFFLINE_PAGE = '/offline.html';

const urlsToCache = [
    '/',
    '/index.html',
    '/manifest.json',
    '/offline.html',
    '/images/icons/uai-resolve-192x192.png',
    '/images/icons/uai-resolve-512x512.png',
    '/css/app.css',
    '/js/app.js'
];

// 📌 Instalação do Service Worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[Service Worker] Adicionando arquivos ao cache');
            return cache.addAll(urlsToCache);
        })
    );
    self.skipWaiting(); // Força ativação imediata do novo Service Worker
});

// 📌 Ativação: Remove caches antigos
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('[Service Worker] Removendo cache antigo:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim(); // Garante que o novo SW assume o controle imediatamente
});

// 📌 Intercepta requisições e aplica a estratégia de cache
self.addEventListener('fetch', (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => caches.match(OFFLINE_PAGE))
        );
    } else {
        event.respondWith(
            fetch(event.request).then((response) => {
                let responseClone = response.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, responseClone);
                });
                return response;
            }).catch(() => caches.match(event.request))
        );
    }
});
