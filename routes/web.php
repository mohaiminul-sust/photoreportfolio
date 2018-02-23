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

Route::group(['prefix' => 'timeline'], function() {
    Route::get('/album', 'HomeController@albumTimeline')->name('timeline.album');
    Route::get('/photo', 'HomeController@photoTimeline')->name('timeline.photo');
    Route::get('/albums', 'HomeController@getTimelineAlbums')->name('timeline.albums');
    Route::get('/photos', 'HomeController@getTimelinePhotos')->name('timeline.photos');
});


Route::group(['prefix' => 'albums'], function() {
    Route::get('/', 'AlbumController@index')->name('album.index');
    Route::get('/all', 'AlbumController@getAlbums')->name('album.all');
    Route::get('/list', 'AlbumController@listAlbums')->name('album.list');
    Route::get('/create', 'AlbumController@create')->name('album.create');
    Route::post('/create', 'AlbumController@store')->name('album.store');
    Route::get('/{id}', 'AlbumController@show')->name('album.show');
    Route::get('/update/{id}', 'AlbumController@edit')->name('album.edit');
    Route::post('/update/{id}', 'AlbumController@update')->name('album.update');
    Route::get('/delete/{id}', 'AlbumController@destroy')->name('album.delete');
    Route::get('/preview/{id}', 'AlbumController@preview')->name('album.preview');
    Route::post('/upload/cover/{id}', 'AlbumController@updateCoverImage')->name('album.uploadcover');
});

Route::group(['prefix' => 'photos'], function() {
    Route::get('/', 'PhotoController@index')->name('photo.index');
    Route::get('/all', 'PhotoController@getPhotos')->name('photo.all');
    Route::get('/{id}', 'PhotoController@show')->name('photo.show');
    Route::get('/upload/image/new', 'PhotoController@uploadImage')->name('photo.uploadimage');
    Route::get('/upload/image/album/{id}', 'PhotoController@uploadImageByAlbum')->name('photo.uploadimagebyalbum');
    Route::post('/create/{id}', 'PhotoController@store')->name('photo.store');
    Route::get('/update/{id}', 'PhotoController@edit')->name('photo.edit');
    Route::post('/update/{id}', 'PhotoController@update')->name('photo.update');
    Route::get('/delete/{id}', 'PhotoController@destroy')->name('photo.delete');
    Route::get('/preview/{id}', 'PhotoController@preview')->name('photo.preview');
    Route::post('/upload/image/{id}', 'PhotoController@updateImage')->name('photo.updateimage');
    Route::get('/album/{id}', 'PhotoController@getPhotosByAlbum')->name('photo.byalbum');
    Route::group(['prefix' => 'tags'], function() {
        Route::post('/create', 'TagController@create')->name('photo.tag.create');
        Route::get('/delete/{id}', 'TagController@destroy')->name('photo.tag.delete');
    });
});