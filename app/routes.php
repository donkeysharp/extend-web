<?php

// Common Routes
Route::get('/', ['uses' => 'HomeController@index']);
Route::get('/login', ['uses' => 'HomeController@login']);
Route::post('/login', ['uses' => 'HomeController@doLogin']);
Route::post('/logout', ['uses' => 'HomeController@doLogout']);

Route::any('/blank', function() {
    return '';
});

Route::get('/public/bulletins/{id}', ['uses' => 'BulletinController@publicDisplay']);

Route::group(['before' => 'auth'], function() {
    Route::get('/dashboard', ['uses' => 'DashboardController@index']);
});

Route::group(['before' => 'auth'], function() {
    Route::get('/subtitles', ['uses' => 'SubtitleController@index']);
    Route::post('/subtitles', ['uses' => 'SubtitleController@store']);
});

Route::group(['before' => 'auth'], function() {
    Route::get('/dashboard/sources', ['uses' => 'SourceController@index']);
    Route::get('/dashboard/sources/create', ['uses' => 'SourceController@create']);
    Route::get('/dashboard/sources/{id}/edit', ['uses' => 'SourceController@edit']);
    Route::get('/sources', ['uses' => 'SourceController@index']);
    Route::post('/sources', ['uses' => 'SourceController@store']);
    Route::put('/sources/{id}', ['uses' => 'SourceController@update']);
    Route::delete('/sources/{id}', ['uses' => 'SourceController@destroy']);
});

Route::group(['before' => 'auth'], function(){
    Route::get('dashboard/news', ['uses' => 'NewsController@index']);
    Route::get('dashboard/news/create', ['uses' => 'NewsController@create']);
    Route::get('dashboard/news/{id}/edit', ['uses' => 'NewsController@edit']);
    Route::get('dashboard/news/{id}/view', ['uses' => 'NewsController@view'])->where('id', '[0-9]+');
    Route::get('/news/extra', ['uses' => 'NewsController@extra']);
    Route::get('/news/{id}', ['uses' => 'NewsController@show'])->where('id', '[0-9]+');
    Route::get('news/{id}/uploads', ['uses' => 'NewsController@getUploads']);
    Route::get('news/{id}/urls', ['uses' => 'NewsController@getURLS']);

    Route::post('/news', ['uses' => 'NewsController@store']);
    Route::put('/news/{id}', ['uses' => 'NewsController@update']);
    Route::delete('/news/{id}', ['uses' => 'NewsController@destroy']);
    Route::delete('/news/{id}/details/{detailId}', ['uses' => 'NewsController@destroyDetail']);
    Route::post('/news/{id}/uploads', ['uses' => 'NewsController@upload']);
    Route::post('/news/{id}/urls', ['uses' => 'NewsController@addURL']);
    Route::post('/news/{id}/copy/{clientId}', ['uses' => 'NewsController@copyNews']);
    Route::delete('/news/{id}/uploads/{uploadId}', ['uses' => 'NewsController@destroyUpload']);
    Route::delete('/news/{id}/urls/{urlId}', ['uses' => 'NewsController@destroyUrl']);

});

Route::group(['before' => 'auth'], function() {
    Route::get('/dashboard/bulletins', ['uses' => 'BulletinController@index']);
    Route::get('/dashboard/bulletins/{id}/order', ['uses' => 'BulletinController@newsOrder']);

    Route::post('/bulletins', ['uses' => 'BulletinController@store']);
    Route::post('/bulletins/{id}/order', ['uses' => 'BulletinController@saveNewsOrder']);
    Route::post('/bulletins/{id}/send', ['uses' => 'BulletinController@sendToClients']);
    Route::post('/bulletins/{id}/send/test', ['uses' => 'BulletinController@sendToTestClient']);
    Route::delete('/bulletins/{id}', ['uses' => 'BulletinController@destroy']);
});

Route::group(['before' => 'auth'], function() {
    Route::get('dashboard/clients', ['uses' => 'ClientController@index']);
    Route::get('dashboard/clients/create', ['uses' => 'ClientController@create']);
    Route::get('dashboard/clients/{id}/edit', ['uses' => 'ClientController@edit']);

    Route::get('/clients', ['uses' => 'ClientController@indexJson']);
    Route::post('/clients', ['uses' => 'ClientController@store']);
    Route::put('/clients/{id}', ['uses' => 'ClientController@update']);
    Route::delete('/clients/{id}', ['uses' => 'ClientController@destroy']);
    Route::post('/clients/{id}/contacts', ['uses' => 'ClientController@storeContact']);
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

Route::group(['before' => 'auth'], function() {
    Route::get('dashboard/reports', ['uses' => 'ReportController@index']);
    Route::get('/reports', ['uses' => 'ReportController@getReport']);
    Route::post('/reports/export', ['uses' => 'ReportController@exportReport']);
    // Route::get('/reports/export/check', ['uses' => 'ReportController@checkReport']);
});

Route::group(['before' => 'auth'], function() {
    Route::get('dashboard/users', ['uses' => 'UserController@index']);
    Route::get('dashboard/users/create', ['uses' => 'UserController@create']);
    Route::get('dashboard/users/{id}/edit', ['uses' => 'UserController@edit']);

    Route::post('/users', ['uses' => 'UserController@store']);
    Route::put('/users/{id}', ['uses' => 'UserController@update']);
    Route::delete('/users/{id}', ['uses' => 'UserController@destroy']);
});

Route::group(['before' => 'auth'], function() {
    Route::get('dashboard/custom/subtitles', ['uses' => 'CustomizeController@subtitles']);
    Route::get('/custom/subtitles/{clientId}', ['uses' => 'CustomizeController@getSubtitlesByClient']);
    Route::post('/custom/subtitles/{clientId}', ['uses' => 'CustomizeController@saveSubtitles']);
});

Route::get('foo', function() {
    $a = new ReportGenerator();
    return $a->report5('2015-09-01', '2015-09-30', 101, 1);
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

Form::macro('literalDate', function($date) {
    $days = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'];
    $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    if (!$date) {
        $date = Carbon\Carbon::now();
    }

    $res = '';
    $res .= $date->day . ' de ' . $months[$date->month - 1] . ' de ' . $date->year;
    return $res;
});
