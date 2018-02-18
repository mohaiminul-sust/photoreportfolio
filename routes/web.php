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
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'albums'], function() {
    Route::get('/', 'AlbumController@index')->name('album.index');
    Route::get('/all', 'AlbumController@getAlbums')->name('album.all');
    Route::get('/create', 'AlbumController@create')->name('album.create');
    Route::post('/create', 'AlbumController@store')->name('album.store');
    Route::get('/{id}', 'AlbumController@show')->name('album.show');
    Route::get('/update/{id}', 'AlbumController@edit')->name('album.edit');
    Route::post('/update/{id}', 'AlbumController@update')->name('album.update');
    Route::get('/delete/{id}', 'AlbumController@destroy')->name('album.delete');
});
