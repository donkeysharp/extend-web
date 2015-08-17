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
    Route::get('dashboard/news/{id}/edit', ['uses' => 'NewsController@edit']);
    Route::get('/news/extra', ['uses' => 'NewsController@extra']);
    Route::get('/news/{id}', ['uses' => 'NewsController@show']);

    Route::post('/news', ['uses' => 'NewsController@store']);
    Route::put('/news/{id}', ['uses' => 'NewsController@update']);
    Route::delete('/news/{id}', ['uses' => 'NewsController@destroy']);
    Route::post('/upload/{id}', ['uses' => 'NewsController@upload']);
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
    Route::get('dashboard/topics/create', ['uses' => 'TopicController@create']);
    Route::get('dashboard/topics/{id}/edit', ['uses' => 'TopicController@edit']);

    Route::post('/topics', ['uses' => 'TopicController@store']);
    Route::put('/topics/{id}', ['uses' => 'TopicController@update']);
    Route::delete('/topics/{id}', ['uses' => 'TopicController@destroy']);
});

Route::group(['before' => 'auth'], function() {
    Route::get('dashboard/export', ['uses' => 'ExportController@index']);
    Route::post('dashboard/export', ['uses' => 'ExportController@export']);
});

Route::get('foo', function() {
    return public_path();
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

Form::macro('tendency', function($type) {
    if ($type == 1) {
        return 'Positiva';
    } else if($type == 2) {
        return 'Negativa';
    } else if($type == 3) {
        return 'Neutra';
    }
    return $type;
});

Form::macro('paginator', function(
    Illuminate\Pagination\Paginator $paginator,
    $url,
    $query=false)
{
    $page = $paginator->getCurrentPage();
    $total = $paginator->getTotal();
    $limit = $paginator->getPerPage();
    $items = $paginator->getItems();
    $lastPage = $paginator->getLastPage();

    $template = '';
    $template .= '<ul class="pagination">';
    $template .= '<li ';
    if ($page === 1) {
        $template .= 'class="disabled"';
    }
    if (!$query) {
        $template .= "><a href=\"$url?page=1\">&laquo;</a></li>";
    } else {
        $template .= "><a href=\"$url&page=1\">&laquo;</a></li>";
    }

    for($i = 1; $i <= $lastPage; $i++) {
        $template .= "<li ";
        if ($i === $page) {
            $template .= 'class="active"';
        }
        if (!$query) {
            $template .= "><a href=\"$url?page=$i\">$i</a></li>";
        } else {
            $template .= "><a href=\"$url&page=$i\">$i</a></li>";
        }
    }
    $template .= "<li ";
    if ($page == $lastPage) {
        $template .= 'class="disabled"';
    }
    if (!$query){
        $template .= "><a href=\"$url?page=$lastPage\">»</a></li>";
    } else {
        $template .= "><a href=\"$url&page=$lastPage\">»</a></li>";
    }
    $template .= "</ul>";

    return $template;
});
