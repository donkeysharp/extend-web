<?php

class TopicController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $topics = Topic::orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($topics->all(), Topic::count(), $limit);
        return View::make('topics.index')
            ->with('topics', $paginator);
    }

    public function create()
    {
        $model = new Topic();
        return View::make('topics.edit')
            ->with('model', $model);
    }

    public function edit($id)
    {
        $topic = Topic::findOrFail($id);
        return View::make('topics.edit')
            ->with('model', $topic);
    }

    public function store()
    {
        $data = Input::all();
        $topic = new Topic();
        $topic->name = $data['name'];
        $topic->description = $data['description'];
        $topic->save();

        if (Request::wantsJson()) {
            return Response::json($topic, 200);
        }

        return Redirect::to('dashboard/topics/'. $topic->id .'/edit')
            ->with('message', 'Tema creado exitosamente');
    }

    public function update($id)
    {
        $data = Input::all();
        $topic = Topic::findOrFail($id);
        $topic->name = $data['name'];
        $topic->description = $data['description'];
        $topic->save();

        return Redirect::to('dashboard/topics/' . $topic->id . '/edit')
            ->with('message', 'Tema actualizado exitosamente');
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete();

        return Response::json([
            'status' => 'ok'
        ], 200);
    }
}
