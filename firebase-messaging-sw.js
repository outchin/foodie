importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js");

firebase.initializeApp({
  apiKey: "AIzaSyCb7oyKywtNkOCaMLd-um-BmQajMsnAsH0",
  authDomain: "snowdeliverywebapp.firebaseapp.com",
  projectId: "snowdeliverywebapp",
  storageBucket: "snowdeliverywebapp.appspot.com",
  messagingSenderId: "snowdeliverywebapp.appspot.com",
  appId: "1:885972486869:web:544125e459de921709305c",
  databaseURL: "https://snowdeliverywebapp-default-rtdb.asia-southeast1.firebasedatabase.app",
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    const promiseChain = clients
        .matchAll({
            type: "window",
            includeUncontrolled: true
        })
        .then(windowClients => {
            for (let i = 0; i < windowClients.length; i++) {
                const windowClient = windowClients[i];
                windowClient.postMessage(payload);
            }
        })
        .then(() => {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.score
              };
            return registration.showNotification(title, options);
        });
    return promiseChain;
});
self.addEventListener('notificationclick', function (event) {
    console.log('notification received: ', event)
});