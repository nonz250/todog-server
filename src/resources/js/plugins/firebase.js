import firebase from "firebase";

const debug = process.env.NODE_ENV !== 'production';

if (firebase.app.length > 0) {
    if (debug) {
        console.log('FCM Init');
    }
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
    firebase.analytics();
}

export default firebase;
