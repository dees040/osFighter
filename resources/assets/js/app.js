
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Laravel.
 */

require('./bootstrap');

Turbolinks.start();

$('.nav-tabs-content a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
});

var el = document.getElementById('sortable-items');

if (el != null) {
    var sortable = Sortable.create(el, {
        handle: ".input-group-addon"
    });
}