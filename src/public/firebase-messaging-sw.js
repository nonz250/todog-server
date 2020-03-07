/**
 * firebase SDK
 */
importScripts('https://www.gstatic.com/firebasejs/7.10.0/firebase-app.js')
importScripts("https://www.gstatic.com/firebasejs/7.10.0/firebase-messaging.js");

firebase.initializeApp({
    apiKey: "AIzaSyAIat47JXQmFW_5d15P5SdA15DSZu2XLwY",
    authDomain: "todog-39b4b.firebaseapp.com",
    databaseURL: "https://todog-39b4b.firebaseio.com",
    projectId: "todog-39b4b",
    storageBucket: "todog-39b4b.appspot.com",
    messagingSenderId: "585069526303",
    appId: "1:585069526303:web:b8242ab9dbe7360427c42a",
    measurementId: "G-V946XT3Y59"
});

/**
 * @type {firebase.messaging.Messaging}
 */
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(payload => {
    console.log(payload)

    let request;
    if (payload.notification === undefined && payload.data === undefined) {
        return Promise;
    }

    if (payload.notification !== undefined) {
        request = payload.notification;
    } else {
        // request = payload.data;
        return Promise;
    }

    return notice(request)
});

/**
 * TODO: フォアグラウンドでバックグラウンド風に通知を出すやり方を調査
 */
self.addEventListener('push', (event) => {
    console.log(event);

    let request;
    if (event.data.json().data === undefined && event.data.json().notification === undefined) {
        return Promise
    }

    if (event.data.json().notification !== undefined) {
        // request = event.data.json().notification;
        return Promise
    } else {
        request = event.data.json().data;
    }
    return event.waitUntil(
        notice(request)
    );
});

/**
 * @param request
 * @returns {Promise<void>}
 */
function notice(request) {
    const title = request.title ? request.title : "Notification Message Title";

    const options = {
        body: request.body ? request.body : "Notification Message body.",
        icon: request.icon ? request.icon : "/labo-round-icon.png",
        onclick: request.click_action ? request.click_action : "https://labo.nozomi.bike",
    };

    return self.registration.showNotification(title, options)
        .catch((error) => {
            console.error(error)
        });
}
