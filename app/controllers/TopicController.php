<?php

class TopicController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $topics = Topic::orderBy('id')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($topics->all(), News::count(), $limit);
        return View::make('topics.index')
            ->with('topics', $paginator);
    }
}
