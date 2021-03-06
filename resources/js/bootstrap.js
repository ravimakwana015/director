
window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

 try {
 	window.$ = window.jQuery = require('jquery');

 	require('bootstrap');
 } catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

 window.axios = require('axios');

 window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

 let token = document.head.querySelector('meta[name="csrf-token"]');

 if (token) {
 	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
 } else {
 	console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
 }

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

 import Echo from 'laravel-echo'

 window.Pusher = require('pusher-js');

 window.Echo = new Echo({
 	// authEndpoint : 'https://joykal.com/demo/producerseye/broadcasting/auth',
 	// authEndpoint : 'https://www.producerseye.com/demo/broadcasting/auth',
 	authEndpoint : '/producerseye/Code/broadcasting/auth',
 	// broadcaster: 'pusher',
 	// key: 'e8cf987cf39f58427d0b',
 	// cluster: 'ap2',
 	// encrypted: true
 	// authEndpoint : '/demo/broadcasting/auth',
 	// authEndpoint : '/broadcasting/auth',
 	broadcaster: 'pusher',
 	key:'anyKEY',
 	wsHost: window.location.hostname,
 	wsPort: 6001,
 	// wssPort: 6006,
 	// disableStats: true,
	encrypted: false,
 	// disabledTransports: ['sockjs', 'xhr_polling', 'xhr_streaming'],
 	// enabledTransports: ['ws', 'wss'],
 });
