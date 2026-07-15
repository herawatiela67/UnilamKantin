const CACHE_NAME = 'unilamkantin-cache-v1';
const assetsToCache = [
    '/',
    '/manifest.json'
];

// Proses Install Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(assetsToCache);
        })
    );
});

// Proses Ambil Data (Fetch)
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});