<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/about', 'HomeController@index')->name('about');

Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::resource('menus', 'MenuController', [
            'except' => ['create', 'store', 'destroy']
        ]);
        Route::resource('groups', 'GroupController');
        Route::resource('pages', 'PageController', [
            'except' => ['create', 'store', 'destroy']
        ]);
    });

});