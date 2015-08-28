<?php

class BulletinController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $bulletins = Bulletin::orderBy('created_at', 'desc')
            ->with(['details'=>function($q) {
                $q->with(['news' => function($q1) {
                    $q1->with('client')
                        ->with('uploads')
                        ->with('urls');
                }]);
            }])
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($bulletins->all(), Client::count(), $limit);
        return View::make('bulletins.index')
            ->with('bulletins', $paginator);
    }

    public function sendToClients($id)
    {
        $bulletin = Bulletin::findOrFail($id);
        $subtitles = DB::table('news_details')->distinct()->get(['subtitle']);
        $details = $bulletin->details()->with(['news' => function($q) {
            $q->with('client');
        }])->get();
        $clientId = $bulletin->client_id;
        $client = Client::findOrFail($clientId);
        $info = [
            'date' => Carbon\Carbon::now(),
            'details' => $details,
            'subtitles'=>$subtitles,
            'client' => $client
        ];

        $contacts = Contact::where('client_id', '=', $clientId)->get();
        Mail::send('bulletins.templates.mosaic', $info, function($message) use($contacts) {
            foreach($contacts as $contact) {
                $message = $message->to($contact->email, $contact->name);
            }
            $message->subject('Boletín Extend');
        });

        return Response::json([
            'status' => 'ok'
        ], 200);
    }

    public function sendToTestClient($id)
    {
        $bulletin = Bulletin::findOrFail($id);
        $subtitles = DB::table('news_details')->distinct()->get(['subtitle']);
        $details = $bulletin->details()->with(['news' => function($q) {
            $q->with('client');
        }])->get();
        $clientId = $bulletin->client_id;
        $client = Client::findOrFail($clientId);
        $info = [
            'date' => Carbon\Carbon::now(),
            'details' => $details,
            'subtitles'=>$subtitles,
            'client' => $client
        ];

        $clientId = 100;
        $contacts = Contact::where('client_id', '=', $clientId)->get();
        if(count($contacts) === 0) {
            return Response::json([
                'status' => 'The client does not have contacts'
            ], 400);
        }
        Mail::send('bulletins.templates.mosaic', $info, function($message) use($contacts) {
            foreach($contacts as $contact) {
                $message = $message->to($contact->email, $contact->name);
            }
            $message->subject('Boletín Extend');
        });

        return Response::json([
            'status' => 'ok'
        ], 200);
    }

    public function publicDisplay($id)
    {
        $bulletin = Bulletin::findOrFail($id);
        $subtitles = DB::table('news_details')->distinct()->get(['subtitle']);
        $details = $bulletin->details()->with(['news' => function($q) {
            $q->with('client');
        }])->get();

        $clientId = $bulletin->client_id;
        $client = Client::findOrFail($clientId);

        return View::make('bulletins.templates.mosaic')
            ->with('date', Carbon\Carbon::now())
            ->with('details', $details)
            ->with('subtitles', $subtitles)
            ->with('client', $client);
    }

    public function store()
    {
        $hasSelected = false;
        $data = Input::all();
        $details = [];
        foreach($data as $key => $value) {
            if (strcmp(substr($key, 0, 15), 'news_detail_id_') !== 0) { continue; }
            $hasSelected = true;
            $details[] = $value;
        }
        if(!$hasSelected) {
            return Redirect::to('/dashboard/news')
                ->with('error', 'Se debe elegir al menos una noticia.');
        }
        if(!Input::get('client_id', false)) {
            return Redirect::to('/dashboard/news')
                ->with('error', 'Cliente no especificado.');
        }

        DB::beginTransaction();
        try {
            $bulletin = new Bulletin();
            $bulletin->client_id = Input::get('client_id');
            $bulletin->save();

            if($hasSelected) {
                $bulletin->details()->attach($details);
            }

        } catch(Exception $e) {
            DB::rollback();
            return $details;
        }
        DB::commit();

        return Redirect::to('dashboard/bulletins')
            ->with('message', 'Boletín creado exitosamente');
    }

    public function destroy($id)
    {
        $bulletin = Bulletin::findOrFail($id);
        $bulletin->delete();

        return Response::json([
            'status' => 'ok'
        ], 200);
    }
}
