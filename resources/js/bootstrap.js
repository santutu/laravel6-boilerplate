window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });


//echo start

// import Echo from 'laravel-echo'
// window.io = require('socket.io-client');
//
// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: window.location.hostname + ':6001',
//     auth: {
//         headers: {
//             Authorization: 'Bearer ' + "123",
//         },
//     },
// });
//
//
// const getChannelName = (channel) => {
//     return channel;
// };
//
// window.Echo.channel(getChannelName('test'))
//     .listen('TestEvent', (e) => {
//         console.log(e);
//     });
//
// window.Echo.join(getChannelName('TestPresenceChannel'))
//     .here((users) => {
//         console.log(users);
//     })
//     .listen('TestPresenceChannelEvent', (e) => {
//         console.log(e);
//     });

// window.Echo.join(getChannelName('electron-app-logged-in'))
//     .here((users) => {
//         console.log(users);
//     })
//echo end
