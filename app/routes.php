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

Route::get('foo', function() {
    $d = '03/01/1990';
    var_dump( DateTime::createFromFormat('d/m/Y', $d) );
});
