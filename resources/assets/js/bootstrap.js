
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo"

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'bb53b4cd703b40ad6c4c',
    cluster: 'eu',
    encrypted: false
});

window.Turbolinks = require("turbolinks");
window.Sortable = require("sortablejs");
window.timeago = require("timeago");
window.trumbowyg = require("trumbowyg");

$.trumbowyg.svgPath = '/images/ui/icons.svg';