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

Route::group(['middleware' => ['auth']], function () {

    Route::post('crimes/crime', 'Crimes\CrimeController@store')->name('crime.store');

    Route::group(['prefix' => 'admin'], function () {
        Route::get('configuration', 'Admin\ConfigurationController@index')->name('config.index');
        Route::put('configuration', 'Admin\ConfigurationController@update')->name('config.update');

        Route::resource('menus', 'Admin\MenuController');
        Route::resource('groups', 'Admin\GroupController');
        Route::resource('ranks', 'Admin\RankController', [
            'only' => 'store'
        ]);
        Route::resource('pages', 'Admin\PageController', [
            'except' => ['create', 'store', 'destroy']
        ]);
        Route::resource('crimes', 'Admin\CrimeController');
    });

});