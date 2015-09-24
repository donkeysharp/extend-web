<?php

class SourceController extends BaseController
{
    public function index()
    {
        $sources = Source::all();

        return Response::json($sources);
    }

    public function store()
    {
        if (!Input::get('source', false)) {
            return Response::json([
                'status' => 'Invalid source value'
            ], 400);
        }
        $source = new Source();
        $source->source = Input::get('source');
        $source->save();

        return Response::json($source, 200);
    }
}
