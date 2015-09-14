<?php
use Carbon\Carbon;

class NewsController extends BaseController
{
    public function index()
    {
        $page = Input::get('page', 1);
        $limit = 30;
        $query = $this->search();


        $newsColumns = ['news.date', 'news.press_note', 'news.code', 'news.clasification', 'news_details.*'];
        if (Input::get('export', false)) {
            $news = $query->get($newsColumns);
            return Excel::create(Carbon::now(), function($excel) use($news) {
                    $excel->sheet('Noticias', function($sheet) use($news) {
                        $sheet->fromArray($news);
                    });
                })->download('xls');
        }
        if (!Input::get('q', false)) {
            $query->skip($limit * ($page - 1))
                        ->take($limit);
        }
        $news = $query->get($newsColumns);

        $paginator = Paginator::make($news->all(), NewsDetail::count(), $limit);
        $clients = Client::where('id', '<>', 100)->get()->lists('name', 'id');
        $media = Media::all()->lists('name', 'id');
        $media[''] = '--- Seleccione un medio ---';
        $clients[''] = '--- Seleccione un cliente ---';


        return View::make('news.index')
            ->with('news', $paginator)
            ->with('model', new SearchQuery(Input::all()))
            ->with('clients', $clients)
            ->with('media', $media);
    }

    private function search()
    {
        $detailsData = [];
        $detailsData['mediaType'] = Input::get('mediaType', false);
        $detailsData['mediaId'] = Input::get('media_id', false);
        $detailsData['title'] = Input::get('title', false);
        $detailsData['tendency'] = Input::get('tendency', false);
        $detailsData['source'] = Input::get('source', false);
        $detailsData['gender'] = Input::get('gender', false);
        $detailsData['show'] = Input::get('show', false);
        $detailsData['description'] = Input::get('description', false);

        $fromDate = Input::get('fromDate', false);
        $toDate = Input::get('toDate', false);
        $searchBy = Input::get('searchBy', false);
        $clientId = Input::get('client_id', false);

        $query = NewsDetail::with([
            'news' => function($q) {
                $q = $q->with('client');
            }
        ]);
        $query->with('media');
        if($detailsData['mediaType']) {
            $query->where('type', '=', $detailsData['mediaType']);
        }
        if($detailsData['mediaId']) {
            $query->where('media_id', '=', $detailsData['mediaId']);
        }
        if($detailsData['title']) {
            $query->where('title', 'like', '%' . $detailsData['title'] .'%');
        }
        if($detailsData['tendency']) {
            $query->where('tendency', '=', $detailsData['tendency']);
        }
        if($detailsData['source']) {
            $query->where('source', 'like', '%'.$detailsData['source'].'%');
        }
        if($detailsData['gender']) {
            $query->where('gender', 'like', '%'.$detailsData['gender'].'%');
        }
        if($detailsData['show']) {
            $query->where('show', 'like', '%'.$detailsData['show'].'%');
        }
        if($detailsData['description']) {
            $query->where('description', 'like', '%'.$detailsData['description'].'%');
        }
        $query->join('news', 'news_details.news_id', '=', 'news.id');

        $dateField = 'date';
        if($searchBy == 'created') {
            $dateField = 'news.created_at';
        }
        // By default search today's news
        $now = Carbon::now();
        $now = $now->year . '-' . $now->month . '-' . $now->day;
        if($fromDate) {
            $fromDate = DateTime::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        } else {
            $fromDate = $now;
        }
        if($toDate) {
            $toDate = DateTime::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');
        } else {
            $toDate = $now;
        }
        $query->where($dateField, '>=', $fromDate);
        $query->where($dateField, '<=', $toDate);

        // Always include coyuntura news

        if ($clientId) {
            $query->where(function($q) use($clientId) {
                $q->where('client_id', '=', 100);
                $q->orWhere('client_id', '=', $clientId);
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    public function show($id)
    {
        $news = News::with('details')->findOrFail($id);

        return Response::json($news, 200);
    }

    public function view($id)
    {
        $news = News::with([
            'details' => function($q) {
                $q->with('media')->with('topic');
            },
            'client'
        ])->findOrFail($id);
        // return $news;
        return View::make('news.view')
            ->with('news', $news);
    }

    public function extra()
    {
        $clients = [];
        $topics = [];
        $media = [];
        $subtitles = [];
        if (Input::get('clients')) {
            $clients = Client::all();
        }
        if (Input::get('topics')) {
            $topics = Topic::all();
        }
        if (Input::get('media')) {
            $media = Media::all();
        }
        if (Input::get('subtitles')) {
            $subtitles = Subtitle::all();
        }

        return Response::json([
            'clients' => $clients,
            'topics' => $topics,
            'media' => $media,
            'subtitles' => $subtitles,
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
        DB::beginTransaction();
        try {
            $data = Input::all();
            $news = News::findOrFail($id);
            $news->client_id = $data['client_id'];
            $news->date = DateTime::createFromFormat('d/m/Y', $data['date']);
            $news->press_note = $data['press_note'];
            $news->clasification = $data['clasification'];
            $news->code = $data['code'];
            $news->save();

            $detailsIds = $this->getDetailsIds($data);
            $data = $this->getNewsDetailInstances($data, $id);

            $newsDetails = NewsDetail::whereIn('id', $detailsIds)->get();
            foreach($data as $item2) {
                if (!isset($item2['id'])) {
                    $newsDetail = new NewsDetail($item2);
                    $newsDetail->news_id = $id;
                    $newsDetail->save();
                    continue;
                }

                foreach($newsDetails as $item) {
                    if ($item->id == $item2['id']) {
                        $item->fill($item2);
                        $item->save();
                        break;
                    }
                }
            }
        } catch(Exception $e) {
            DB::rollback();
            throw new Exception($e);
        }
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

        $isNewsFooter = Input::get('newsFooter', false) ? true : false;

        $file = Input::file('file');
        $extension = File::extension($file->getClientOriginalName());
        $directory = public_path() . '/uploads';
        $filename =  $file->getClientOriginalName();

        $upload_success = Input::file('file')->move($directory, $filename);

        $upload = new NewsUpload();
        $upload->type = File::extension($file->getClientOriginalName());;
        $upload->news_id = $id;
        $upload->file_name = $filename;
        $upload->news_footer = $isNewsFooter;
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

    public function destroyUpload($id, $uploadId)
    {
        $news = News::findOrFail($id);
        $upload = NewsUpload::where('news_id', '=', $id)
            ->where('id', '=', $uploadId)
            ->get()->first();

        if ($upload) {
            $upload->delete();
            return Response::json([
                'status' => 'ok'
            ], 200);
        }

        return Response::json([
            'status' => 'Upload not found'
        ], 404);
    }

    public function destroyUrl($id, $urlId)
    {
        $news = News::findOrFail($id);
        $url = NewsUrl::where('news_id', '=', $id)
            ->where('id', '=', $urlId)
            ->get()->first();

        if ($url) {
            $url->delete();
            return Response::json([
                'status' => 'ok'
            ], 200);
        }
        return Response::json([
            'status' => 'URL not found'
        ], 404);
    }

    private function getDetailsIds($data)
    {
        $ids = [];
        if ($detailData = $data['media']['printed']) {
            if(isset($detailData['id'])) {
                $ids[] = $detailData['id'];
            }
        }
        if ($detailData = $data['media']['digital']) {
            if(isset($detailData['id'])) {
                $ids[] = $detailData['id'];
            }
        }
        if ($detailData = $data['media']['radio']) {
            if(isset($detailData['id'])) {
                $ids[] = $detailData['id'];
            }
        }
        if ($detailData = $data['media']['tv']) {
            if(isset($detailData['id'])) {
                $ids[] = $detailData['id'];
            }
        }
        if ($detailData = $data['media']['source']) {
            if(isset($detailData['id'])) {
                $ids[] = $detailData['id'];
            }
        }
        return $ids;
    }

    private function getNewsDetailInstances($data, $newsId)
    {
        $result = [];
        if ($detailData = $data['media']['printed']) {
            $result[] = $detailData;
        }
        if ($detailData = $data['media']['digital']) {
            $result[] = $detailData;
        }
        if ($detailData = $data['media']['radio']) {
            $result[] = $detailData;
        }
        if ($detailData = $data['media']['tv']) {
            $result[] = $detailData;
        }
        if ($detailData = $data['media']['source']) {
            $result[] = $detailData;
        }

        return $result;
    }
}
