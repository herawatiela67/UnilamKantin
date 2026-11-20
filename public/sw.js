const CACHE_NAME = 'unilamkantin-v1';

// Proses Install Service Worker
self.addEventListener('install', event => {
    // Langsung aktifkan service worker baru tanpa menunggu tab lama ditutup
    self.skipWaiting();
});

// Proses Aktifasi & Bersihkan Cache Lama
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(name => name !== CACHE_NAME)
                    .map(name => caches.delete(name))
            );
        })
    );
    self.clients.claim();
});

// Proses Ambil Data (Fetch)
self.addEventListener('fetch', event => {
    // Hanya tangani request GET; biarkan POST/PUT/DELETE langsung ke server
    if (event.request.method !== 'GET') {
        return;
    }

    // Utamakan ambil data terbaru dari jaringan agar status stan & pesanan selalu sinkron
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});