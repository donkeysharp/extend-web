<?php

class MediaController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $media = Media::orderBy('id')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($media->all(), Media::count(), $limit);
        return View::make('media.index')
            ->with('media', $paginator);
    }
}
