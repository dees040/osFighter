<?php

/*
|--------------------------------------------------------------------------
| Dynamic Routes
|--------------------------------------------------------------------------
|
| Here is where the dynamic routes will be registered. These routes
| are loaded by the RouteServiceProvider. This routes can be edited
| in the admin section of the application.
|
*/

foreach (game()->pages() as $page) {
    call_dynamic_route($page)->name($page->route_name);
}