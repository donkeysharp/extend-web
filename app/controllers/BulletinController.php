<?php

class BulletinController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $bulletins = Bulletin::orderBy('created_at', 'desc')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($bulletins->all(), Client::count(), $limit);
        return View::make('bulletins.index')
            ->with('bulletins', $paginator);
    }

    public function show($id)
    {
        return $id;
    }

    public function publicDisplay($id)
    {

        return View::make('bulletins.templates.mosaic')
            ->with('foo', 'bar');
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
