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

    public function show($id)
    {
        $news = News::findOrFail($id);

        return Response::json($news, 200);
    }

    public function extra()
    {
        $clients = [];
        $topics = [];
        $media = [];
        if (Input::get('clients')) {
            $clients = Client::all();
        }
        if (Input::get('topics')) {
            $topics = Topic::all();
        }
        if (Input::get('media')) {
            $media = Media::all();
        }

        return Response::json([
            'clients' => $clients,
            'topics' => $topics,
            'media' => $media,
        ], 200);
    }

    public function create()
    {
        return View::make('news.edit')
            ->with('id', null);
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

    public function edit($id)
    {
        $news = News::findOrFail($id);

        return View::make('news.edit')
            ->with('id', $news->id);
    }

    public function update($id)
    {
        $data = Input::all();
        $news = News::findOrFail($id);
        $news->client_id = $data['client_id'];
        $news->date = DateTime::createFromFormat('d/m/Y', $data['date']);
        $news->press_note = $data['press_note'];
        $news->subtitle = $data['subtitle'];
        $news->clasification = $data['clasification'];
        $news->code = $data['code'];

        $newsDetails = $this->getNewsDetailInstances($data, $id);
        DB::transaction(function() use($news, $newsDetails, $data, $id) {
            $news->save();

            NewsDetail::where('news_id', $id)->delete();

            foreach($newsDetails as $item) {
                $item->save();
            }
        });
        DB::commit();

        return Response::json($newsDetails, 200);
    }

    private function getNewsDetailInstances($data, $newsId)
    {
        $result = [];
        if ($detailData = $data['media']['printed']) {
            $result[] = NewsDetail::createInstance(NewsDetail::PRINTED, $detailData, $newsId);
        }
        if ($detailData = $data['media']['digital']) {
            $result[] = NewsDetail::createInstance(NewsDetail::DIGITAL, $detailData, $newsId);
        }
        if ($detailData = $data['media']['radio']) {
            $result[] = NewsDetail::createInstance(NewsDetail::RADIO, $detailData, $newsId);
        }
        if ($detailData = $data['media']['tv']) {
            $result[] = NewsDetail::createInstance(NewsDetail::TV, $detailData, $newsId);
        }
        if ($detailData = $data['media']['source']) {
            $result[] = NewsDetail::createInstance(NewsDetail::SOURCE, $detailData, $newsId);
        }

        return $result;
    }
}
