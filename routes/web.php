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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::get('configuration', 'Admin\ConfigurationController@index')->name('config.index');
        Route::put('configuration', 'Admin\ConfigurationController@update')->name('config.update');

        Route::resource('menus', 'Admin\MenuController');
        Route::resource('groups', 'Admin\GroupController');
        Route::resource('ranks', 'Admin\RankController', [
            'only' => 'store'
        ]);
        Route::resource('locations', 'Admin\LocationController', [
            'only' => 'store'
        ]);
        Route::resource('routes', 'Admin\RouteController', [
            'except' => ['create', 'store', 'destroy']
        ]);
        Route::resource('crimes', 'Admin\CrimeController');
        Route::resource('cars', 'Admin\CarController');
        Route::resource('users', 'Admin\UserController');
    });

});