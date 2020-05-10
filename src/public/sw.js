const CACHE_NAME = 'todog-cache-v0.0.2';
const urlsToCache = [
  '/js/app.js'
];

self.addEventListener('install', install);
self.addEventListener('activate', activate);

/**
 * SW インストール処理
 * @param event
 */
function install(event) {
  console.log('install');
  event.waitUntil(self.skipWaiting());
  event.target.registration.addEventListener('updatefound', updatefound);
  registerCache(event);
}

/**
 * SW 更新検知処理
 * @param event
 */
function updatefound(event) {
  console.log('updatefound');
}

/**
 * SW 削除処理
 * @param event
 */
function activate(event) {
  console.log('activateNewServiceWorker');

  const cacheWhitelist = [CACHE_NAME];

  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          // ホワイトリストにないキャッシュ(古いキャッシュ)は削除する
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            console.log('delete - ' + cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
}

/**
 * キャッシュにjsを登録
 * @param event
 */
function registerCache(event) {
  return event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function (cache) {
        urlsToCache.map(url => {
          return fetch(new Request(url)).then(response => {
            console.log(url);
            return cache.put(url, response);
          });
        });
      })
      .catch(function (err) {
        console.log(err);
      })
  );
}

self.addEventListener('message', function (event) {
  console.log('message');
  return install(event);
});

self.addEventListener('fetch', function (event) {
  console.log('fetch');
  // console.log(event);
  // const result = self.registration.update();
  // console.log(result);
});

self.addEventListener('push', function (event) {
  console.log('push');
  console.log(event);
});

self.addEventListener('controllerchange', function (event) {
  console.log('controllerchange');
  console.log(event);
});

self.addEventListener('notificationclick', (event) => {
  console.log('notificationclick');
  console.log(event);
});

self.addEventListener('statechange', (event) => {
  console.log('statechange');
  console.log(event);
});
