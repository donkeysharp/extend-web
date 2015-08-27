<?php

class SubtitleController extends BaseController
{
    public function index()
    {
        $subtitles = Subtitle::all();

        return Response::json($subtitles, 200);
    }

    public function store()
    {
        $rules = ['subtitle'=>'required'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'Subtitulo requerido'
            ], 400);
        }

        $subtitle = new Subtitle();
        $subtitle->subtitle = Input::get('subtitle');
        $subtitle->save();

        return Response::json($subtitle, 200);
    }
}
