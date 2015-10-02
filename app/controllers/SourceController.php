<?php

class SourceController extends BaseController
{
    public function index()
    {
        if (Request::wantsJson()) {
            $sources = Source::all();
            return Response::json($sources);
        }

        $limit = 10; $page = Input::get('page', 1);

        $sources = Source::orderBy('source')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($sources->all(), Source::count(), $limit);
        return View::make('sources.index')
            ->with('sources', $paginator);
    }

    public function create()
    {
        $source = new Source();
        return View::make('sources.edit')
            ->with('model', $source);
    }

    public function store()
    {
        if (!Input::get('source', false)) {
            if (Request::wantsJson()) {
                return Response::json([
                    'status' => 'Invalid source value'
                ], 400);
            }
            return Redirect::to('dashboard/create')
                ->with('error', 'Datos inválidos');
        }
        $source = new Source();
        $source->source = Input::get('source');
        $source->description = Input::get('description', null);
        $source->save();

        if (Request::wantsJson()) {
            return Response::json($source, 200);
        }
        return Redirect::to('dashboard/sources')
            ->with('message', 'Fuente creada exitosamente');
    }

    public function edit($id)
    {
        $source = Source::findOrFail($id);

        return View::make('sources.edit')
            ->with('model', $source);
    }

    public function update($id)
    {
        if (!Input::get('source', false)) {
            return Redirect::to('dashboard/create')
                ->with('error', 'Datos inválidos');
        }

        $source = Source::findOrFail($id);
        $source->source = Input::get('source');
        $source->description = Input::get('description', null);
        $source->save();

        return Redirect::to('dashboard/sources')
            ->with('message', 'Fuente actualizada exitosamente');
    }

    public function destroy($id)
    {
        $source = Source::findOrFail($id);
        $source->delete();

        return Response::json([
            'status' => 'ok'
        ], 200);
    }

}
