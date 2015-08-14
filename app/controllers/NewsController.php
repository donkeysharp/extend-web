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

    public function store()
    {
        $data = Input::all();
        $news = new News();
        $news->client_id = $data['client_id'];
        $news->date = DateTime::createFromFormat('d/m/Y', $data['date']);
        $news->press_note = $data['press_note'];
        $news->subtitle = $data['subtitle'];
        $news->clasification = $data['clasification'];
        $news->code = $data['code'];
        $news->save();

        return Response::json($news, 200);
    }

}
