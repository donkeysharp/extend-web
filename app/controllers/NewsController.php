<?php

class NewsController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $news = News::with('client')
            ->with('details')
            ->with([
                'details' => function($q) {
                    $q->with('media');
                }
            ])
            ->orderBy('date', 'desc')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($news->all(), News::count(), $limit);

        return View::make('news.index')
            ->with('news', $paginator);
    }

    public function show($id)
    {
        $news = News::with('details')->findOrFail($id);

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
        $news->details = $newsDetails;
        return Response::json($news, 200);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return Response::json([
            'status' => 'ok'
        ], 200);
    }

    public function getUploads($id)
    {
        $news = News::findOrFail($id);
        $uploads = $news->uploads()->get();
        return Response::json($uploads);
    }

    public function upload($id)
    {
        $news = News::findOrFail($id);

        $file = Input::file('file');
        $extension = File::extension($file->getClientOriginalName());
        $directory = public_path() . '/uploads';
        $filename =  $file->getClientOriginalName();

        $upload_success = Input::file('file')->move($directory, $filename);

        $upload = new NewsUpload();
        $upload->type = File::extension($file->getClientOriginalName());;
        $upload->news_id = $id;
        $upload->file_name = $filename;
        $upload->save();

        return Response::json($upload, 200);
    }

    public function getURLS($id)
    {
        $news = News::findOrFail($id);
        $urls = $news->urls()->get();
        return Response::json($urls);
    }

    public function addURL($id)
    {
        $news = News::findOrFail($id);
        $url = new NewsUrl();
        $url->url = Input::get('url');
        $url->news_id = $id;
        $url->save();

        return Response::json($url, 200);
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
