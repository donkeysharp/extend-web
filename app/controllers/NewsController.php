<?php

class NewsController extends BaseController
{
    public function index()
    {
        $limit = 3; $page = Input::get('page', 1);

        $query = News::with('client')
            ->with([
                'details' => function($q) {
                    $q->with('media');
                }
            ]);
        if (Input::get('q')) {
            $limit = 50;
            $query = $this->search();
        }

        $news = $query->orderBy('date', 'desc')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($news->all(), News::count(), $limit);
        $clients = Client::all()->lists('name', 'id');
        $media = Media::all()->lists('name', 'id');
        $media[''] = '--- Seleccione un medio ---';
        $clients[''] = '--- Seleccione un cliente ---';

        return View::make('news.index')
            ->with('news', $paginator)
            ->with('model', Input::all())
            ->with('clients', $clients)
            ->with('media', $media);
    }

    private function search()
    {
        $fromDate = Input::get('fromDate', false);
        $toDate = Input::get('toDate', false);
        $searchBy = Input::get('searchBy', false);
        $clientId = Input::get('client_id', false);
        $detailsData = [
            'mediaType' => Input::get('mediaType', false),
            'mediaId' => Input::get('media_id', false),
            'title' => Input::get('title', false),
            'tendency' => Input::get('tendency', false),
            'source' => Input::get('source', false),
            'gender' => Input::get('gender', false),
            'show' => Input::get('show', false),
            'description' => Input::get('description', false),
        ];

        $query = News::with([
            'client' => function($q) use($clientId) {
                if($clientId) {
                    $q->where('id', '=', $clientId);
                }
            },
            'details' => function($q) use($detailsData) {
                if($detailsData['mediaType']) {
                    $q->where('type', '=', $detailsData['mediaType']);
                }
                if($detailsData['mediaId']) {
                    $q->where('media_id', '=', $detailsData['mediaId']);
                }
                if($detailsData['title']) {
                    $q->where('title', 'like', '%' . $detailsData['title'] .'%');
                }
                if($detailsData['tendency']) {
                    $q->where('tendency', '=', $detailsData['tendency']);
                }
                if($detailsData['source']) {
                    $q->where('source', 'like', '%'.$detailsData['source'].'%');
                }
                if($detailsData['gender']) {
                    $q->where('gender', 'like', '%'.$detailsData['gender'].'%');
                }
                if($detailsData['show']) {
                    $q->where('show', 'like', '%'.$detailsData['show'].'%');
                }
                if($detailsData['description']) {
                    $q->where('description', 'like', '%'.$detailsData['description'].'%');
                }
            }
        ]);

        $dateField = 'date';
        if($searchBy == 'created') {
            $dateField = 'created_at';
        }
        if($fromDate) {
            $fromDate = DateTime::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
            $query->where($dateField, '>=', $fromDate);
        }
        if($toDate) {
            $toDate = DateTime::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');
            $query->where($dateField, '<=', $toDate);
        }
        if($clientId) {
            $query->where('client_id', '=', $clientId);
        }

        // $result = $query->get();
        // print_r(DB::getQueryLog());

        return $query;
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
        // BUG FIX. No subtitle required for news. It should be moved to news details
        $news->subtitle = '';
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
        $news->subtitle = '';
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

    public function destroyDetail($id, $detailId)
    {
        $news = News::findOrFail($id);
        $newsDetail = NewsDetail::findOrFail($detailId);
        $newsDetail->delete();

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
