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
        $details = $bulletin->details()->with(['news' => function($q) {
            $q->with('client');
        }])->get();

        $clientId = $details[0]->news->client_id;
        $contacts = Contact::where('client_id', '=', $clientId)->get();
        $info = ['date' => Carbon\Carbon::now(), 'details' => $details];

        foreach($contacts as $contact) {
            Mail::send('bulletins.templates.mosaic', $info, function($message) use($contact) {
                $message->to($contact->email, $contact->name)->subject('Boletín Extend');
            });
        }

        return Response::json([
            'status' => 'ok'
        ], 200);
    }

    public function publicDisplay($id)
    {
        $bulletin = Bulletin::findOrFail($id);
        $details = $bulletin->details()->with(['news' => function($q) {
            $q->with('client');
        }])->get();
        // return $details;
        return View::make('bulletins.templates.mosaic')
            ->with('date', Carbon\Carbon::now())
            ->with('details', $details);
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $data = Input::all();
            $bulletin = new Bulletin();
            $bulletin->save();
            $details = [];
            foreach($data as $key => $value) {
                if (strcmp(substr($key, 0, 15), 'news_detail_id_') !== 0) { continue; }
                $details[] = $value;
            }
            $bulletin->details()->attach($details);

        } catch(Exception $e) {
            DB::rollback();
            throw new Exception($e);
        }

        DB::commit();
        return Redirect::to('dashboard/news')
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
