<?php

// Common Routes
Route::get('/', ['uses' => 'HomeController@index']);
Route::get('/login', ['uses' => 'HomeController@login']);
Route::post('/login', ['uses' => 'HomeController@doLogin']);
Route::post('/logout', ['uses' => 'HomeController@doLogout']);


Route::group(['before' => 'auth'], function() {
    Route::get('/dashboard', ['uses' => 'DashboardController@index']);
});

Route::group(['before' => 'auth'], function(){
    Route::get('dashboard/news', ['uses' => 'NewsController@index']);
    Route::get('dashboard/news/create', ['uses' => 'NewsController@create']);
    Route::post('/news', ['uses' => 'NewsController@store']);
    Route::put('/news/{id}', ['uses' => 'NewsController@update']);
    Route::get('dashboard/news/{id}/edit', ['uses' => 'NewsController@edit']);
    Route::get('/news/extra', ['uses' => 'NewsController@extra']);
    Route::get('/news/{id}', ['uses' => 'NewsController@show']);
});

Route::group(['before' => 'auth'], function() {
    Route::get('dashboard/clients', ['uses' => 'ClientController@index']);
    Route::get('dashboard/clients/create', ['uses' => 'ClientController@create']);
    Route::get('dashboard/clients/{id}/edit', ['uses' => 'ClientController@edit']);

    Route::post('/clients', ['uses' => 'ClientController@store']);
    Route::put('/clients/{id}', ['uses' => 'ClientController@update']);
    Route::delete('/clients/{id}', ['uses' => 'ClientController@destroy']);
});

Route::group(['before' => 'auth'], function() {
    Route::get('dashboard/media', ['uses' => 'MediaController@index']);
    Route::get('dashboard/media/create', ['uses' => 'MediaController@create']);
    Route::get('dashboard/media/{id}/edit', ['uses' => 'MediaController@edit']);

    Route::post('/media', ['uses' => 'MediaController@store']);
    Route::put('/media/{id}', ['uses' => 'MediaController@update']);
    Route::delete('/media/{id}', ['uses' => 'MediaController@destroy']);
});

Route::group(['before' => 'auth'], function() {
    Route::get('dashboard/topics', ['uses' => 'TopicController@index']);
});

Route::get('foo', function() {
    $d = '03/01/1990';
    var_dump( DateTime::createFromFormat('d/m/Y', $d) );
});


Form::macro('mediaType', function($type) {
    if ($type == 1) {
        return 'Impreso';
    } else if($type == 2) {
        return 'Digital';
    } else if($type == 3) {
        return 'Radio';
    } else if($type == '4') {
        return 'TV';
    } else if($type == 5) {
        return 'Fuente';
    }
    return $type;
});
