/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Laravel.
 */

require('./bootstrap');

Turbolinks.start();

$("time.timeago").timeago();

$('.nav-tabs-content a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');

    var id = $(this).attr('href');
    var url = window.location.href.split('#')[0];

    window.history.pushState('', '', url + id);
});

if (window.location.href.includes('#tab-')) {
    var tab = window.location.href.split('#').pop();

    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
}

var el = document.getElementById('sortable-items');

if (el != null) {
    var sortable = Sortable.create(el, {
        handle: ".input-group-addon"
    });
}

$('.game-countdown').each(function () {
    var $el = $(this),
        value = $el.text(),
        valueToExtract = $(this).data('extract');

    if (typeof valueToExtract == 'undefined') {
        valueToExtract = 1;
    }

    var timer = setInterval(function () {
        value = value - valueToExtract;
        $el.text(value);

        if (value <= 0) {
            clearInterval(timer);
        }
    }, 1000);
});

Echo.channel('shout_box')
    .listen('ShoutBoxMessageCreated', event => {
        var $row = '<tr class="message"><td>' + event.user + ' </td><td class="col-xs-9">' + event.message.body + '</td><td><time class="timeago" datetime="' + event.message.created_at + '">' + event.message.created_at + '</time></td></tr>';

        $('.shout-box table').prepend($row);

        $("time.timeago").timeago();
    });