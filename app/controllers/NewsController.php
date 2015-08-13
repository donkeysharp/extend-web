<?php

class NewsController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $news = News::orderBy('id')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($news->all(), News::count(), $limit);

        return View::make('news.index')
            ->with('news', $paginator);
    }

    public function create()
    {
        return View::make('news.edit');
    }

}
