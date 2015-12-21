<?php
use Carbon\Carbon;

class NewsController extends BaseController
{
    public function index()
    {
        $page = Input::get('page', 1);
        $limit = 300;
        $query = $this->search();

        $newsColumns = ['news.date', 'news.press_note', 'news.code', 'news.clasification', 'news_details.*'];
        if (Input::get('export', false)) {
            $news = $query->get(['news_details.*']);
            return Excel::create(Carbon::now(), function($excel) use($news) {
                    $excel->sheet('Noticias', function($sheet) use($news) {
                        $clientId = Input::get('client_id', false);
                        $client = Client::where('id', '=', $clientId)->get()->first();
                        $sheet->setAutoSize(false);
                        $sheet->getStyle('A2:S2' . $sheet->getHighestRow())
                                    ->getAlignment()->setWrapText(true);
                        $sheet->setHeight(2, 46);
                        $sheet->row(2, function($row) {
                            $row->setFont(['bold' => true]);
                        });
                        $sheet->cells('A2:N1', function($cells) {
                            $cells->setAlignment('center');
                        });
                        $sheet->cells('A2:N2', function($cells) {
                            $cells->setValignment('middle');
                        });
                        $sheet->mergeCells('A1:N1');

                        $sheet->row(1, ['']);
                        if ($client) {
                            $sheet->row(1, [$client->name]);
                        }

                        $sheet->setWidth('A', 5);
                        $sheet->setWidth('B', 10);
                        $sheet->setWidth('C', 17.44);
                        $sheet->setWidth('D', 5);
                        $sheet->setWidth('E', 16.11);
                        $sheet->setWidth('F', 28.34);
                        $sheet->setWidth('G', 8.58);
                        $sheet->setWidth('H', 13);
                        $sheet->setWidth('I', 20.25);
                        $sheet->setWidth('J', 10.5);
                        $sheet->setWidth('K', 9.91);
                        $sheet->setWidth('L', 12.14);
                        $sheet->setWidth('M', 12.14);
                        $sheet->setWidth('N', 12.14);
                        $sheet->setWidth('S', 12.14);
                        $sheet->row(2, [
                            'N°', 'FECHA', 'CLIENTE', 'OJO', 'MEDIO', 'TÍTULO ARTÍCULO',
                            'Pixeles/ CM. COL', 'Equivalencia Publicitaria en dólares',
                            'TEMA', 'TENDENCIA', 'TIPO', 'SECCIÓN', 'PÁG', 'CÓDIGO',
                            'FUENTE', 'TENDENCIA', 'ALIAS', 'GÉNERO', 'SUBTÍTULO'
                        ]);
                        $row = 3;
                        foreach ($news as $item) {
                            $data = [];
                            $data[] = $row-1;
                            $data[] = $item->news->date;
                            if ($item->news->client) {
                                $data[] = $item->news->client->name;
                            } else {
                                $data[] = '';
                            }
                            $data[] = $item->news->clasification;
                            if ($item->media) {
                                $data[] = $item->media->name;
                            } else {
                                $data[] = '';
                            }
                            $data[] = $item->title;
                            $data[] = $item->measure;
                            $data[] = $item->cost;
                            if ($item->topic) {
                                $data[] = $item->topic->name;
                            } else {
                                $data[] = '';
                            }
                            $tendency = '';
                            if ($item->tendency == '1') { $tendency = 'Positivo'; }
                            else if($item->tendency == '2') { $tendency = 'Negativo'; }
                            else if($item->tendency == '3') {$tendency = 'Neutro'; }
                            $data[] = $tendency;
                            $type = '';
                            if ($item->type == '1') {$type = 'Impreso'; }
                            else if ($item->type == '2') { $type = 'Digital'; }
                            else if ($item->type == '3') { $type = 'Radio'; }
                            else if ($item->type == '4') { $type = 'Televisión'; }
                            $data[] = $type;
                            $data[] = $item->section;
                            $data[] = $item->page;
                            $data[] = $item->code;
                            $data[] = $item->source;
                            $tendency = '';
                            if ($item->sourceTendency == '1') { $tendency = 'Positivo'; }
                            else if($item->sourceTendency == '2') { $tendency = 'Negativo'; }
                            else if($item->sourceTendency == '3') {$tendency = 'Neutro'; }
                            $data[] = $tendency;
                            $data[] = $item->alias;
                            $data[] = $item->gender;
                            $data[] = $item->subtitle;
                            $sheet->row($row++, $data);
                        }
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
        $query->with('media')->with('topic');
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
        $sources = [];
        if (Input::get('clients')) {
            $clients = Client::orderBy('name')->get()->all();
        }
        if (Input::get('topics')) {
            $topics = Topic::orderBy('name')->get()->all();
        }
        if (Input::get('media')) {
            $media = Media::orderBy('name')->get()->all();
        }
        if (Input::get('subtitles')) {
            $subtitles = Subtitle::orderBy('subtitle')->get()->all();
        }
        if (Input::get('sources')) {
            $sources = Source::orderBy('source')->get()->all();
        }

        return Response::json([
            'clients' => $clients,
            'topics' => $topics,
            'media' => $media,
            'subtitles' => $subtitles,
            'sources' => $sources,
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

    public function copyNews($id, $clientId)
    {
        // It gives an extra 'date2' column to avoid
        // date parsing as News model accessor changes date format
        $news = News::with('details')
            ->with('urls')
            ->with('uploads')
            ->select(['news.*', 'date as date2'])
            ->findOrFail($id);

        $copiedObject = new News();
        DB::beginTransaction();
        try {
            $copiedObject->press_note = $news->press_note;
            $copiedObject->code = $news->code;
            $copiedObject->clasification = $news->clasification;
            $copiedObject->date = $news->date2;
            $copiedObject->client_id = $clientId;
            $copiedObject->save();

            foreach ($news->details as $detail) {
                $copiedDetail = new NewsDetail();
                $copiedDetail->type = $detail->type;
                $copiedDetail->title = $detail->title;
                $copiedDetail->description = $detail->description;
                $copiedDetail->tendency = $detail->tendency;
                $copiedDetail->section = $detail->section;
                $copiedDetail->page = $detail->page;
                $copiedDetail->gender = $detail->gender;
                $copiedDetail->web = $detail->web;
                $copiedDetail->source = $detail->source;
                $copiedDetail->alias = $detail->alias;
                $copiedDetail->measure = $detail->measure;
                $copiedDetail->cost = $detail->cost;
                $copiedDetail->subtitle = $detail->subtitle;
                $copiedDetail->communication_risk = $detail->communication_risk;
                $copiedDetail->show = $detail->show;
                $copiedDetail->news_id = $copiedObject->id;
                $copiedDetail->topic_id = $detail->topic_id;
                $copiedDetail->media_id = $detail->media_id;
                $copiedDetail->save();
            }

            foreach ($news->uploads as $upload) {
                $copiedUpload = new NewsUpload();
                $copiedUpload->type = $upload->type;
                $copiedUpload->file_name = $upload->file_name;
                $copiedUpload->news_id = $copiedObject->id;
                $copiedUpload->save();
            }

            foreach ($news->urls as $url) {
                $copiedUrl = new NewsUrl();
                $copiedUrl->url = $url->url;
                $copiedUrl->news_id = $copiedObject->id;
                $copiedUrl->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::json([
                'status' => $e->getMessage()
            ], 500);
        }

        return Response::json($copiedObject, 200);

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
        $filename =  parent::generateGUID() . '.' . $extension;

        $upload_success = Input::file('file')->move($directory, $filename);

        $upload = new NewsUpload();
        $upload->type = $extension;
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
