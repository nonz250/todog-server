import constants from './constants';

export default class SwController {

  static get SERVICE_WORKER_JS() {
    return '/sw.js';
  }

  get registration() {
    return this._registration;
  }

  set registration(value) {
    this._registration = value;
  }

  constructor() {
  }

  async init() {
    this.registration = await this.getRegistration();
    return this;
  }

  async getRegistration() {
    if ('serviceWorker' in navigator) {
      if (constants.isDebug()) {
        console.log('serviceWorker exists.');
      }

      return await navigator.serviceWorker
        .getRegistration()
        .then((registration) => {
          return registration;
        });
    }
  }

  async registerInstallListener(callback) {
    await this.registration.addEventListener('install', (event) => {
      if (constants.isDebug()) {
        console.log('install');
        console.log(callback);
      }
      event.skipWaiting();
    });

    return this;
  }

  async registerUpdateListener(callback) {
    await this.registration.addEventListener('updatefound', (event) => {
      if (constants.isDebug()) {
        console.log('updatefound');
        console.log(event);
      }
      callback();
    });

    return this;
  }

  async updateApp() {
    this.registration.update();
    return this;
  }

  async installApp() {
    if ('serviceWorker' in navigator) {
      if (constants.isDebug()) {
        console.log('serviceWorker exists.');
      }

      this.registration = await navigator.serviceWorker
        .register(SwController.SERVICE_WORKER_JS)
        .then((registration) => {
          if (constants.isDebug()) {
            console.log('serviceWorker installed.');
          }
          return registration;
        })
        .catch((error) => {
          console.error(error);
        })
        .finally(() => {
        });

      return this;
    }
  }

  async activateApp() {
    await this.registration.skipWaiting();
    // const cacheWhitelist = [CACHE_NAME];
    //
    // event.waitUntil(
    //     caches.keys().then((cacheNames) => {
    //         return Promise.all(
    //             cacheNames.map((cacheName) => {
    // ホワイトリストにないキャッシュ(古いキャッシュ)は削除する
    // if (cacheWhitelist.indexOf(cacheName) === -1) {
    //     console.log('delete - ' + cacheName);
    //     return caches.delete(cacheName);
    // }
    // })
    // );
    // })
    // );
    return this;
  }
}
