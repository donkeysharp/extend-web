<?php

class ReportGenerator
{
    const PRINTED = 1;
    const DIGITAL = 2;
    const RADIO  = 3;
    const TV = 4;
    const SOURCE = 5;

    private function getFilterByMediaQuery($query, $filterByMedia)
    {
        if ($filterByMedia == self::PRINTED || $filterByMedia == self::DIGITAL) {
            $query->where(function($q)
            {
                $q->where('nd.type', '=', 1)
                    ->orWhere('nd.type', '=', 2);
            });
        } else if ($filterByMedia == self::RADIO) {
            $query->where('nd.type', '=', 3);
        } else if ($filterByMedia == self::TV) {
            $query->where('nd.type', '=', 4);
        } else if ($filterByMedia == self::SOURCE) {
            $query->where('nd.type', '=', 5);
        }

        return $query;
    }

    /**
     * [report1 description]
     * @param  [type] $from          [description]
     * @param  [type] $to            [description]
     * @param  [type] $clientId      [description]
     * @param  [type] $filterByMedia [description]
     * @return [type]                [description]
     */
    public function report1($from, $to, $clientId, $filterByMedia)
    {
        $query = DB::table('media as m')
            ->join('news_details as nd', 'm.id', '=', 'nd.media_id')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId);
        $query = $this->getFilterByMediaQuery($query, $filterByMedia);
        $result = $query->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('m.name')
            ->orderBy('m.name')
            ->select(DB::raw('m.name, count(nd.id) as news'))
            ->get();

        return $result;
    }

    /**
     * [report2 description]
     * @param  [type] $from          [description]
     * @param  [type] $to            [description]
     * @param  [type] $clientId      [description]
     * @param  [type] $filterByMedia [description]
     * @return [type]                [description]
     */
    public function report2($from, $to, $clientId, $filterByMedia)
    {
        $query = DB::table('topics as t')
            ->join('news_details as nd', 't.id', '=', 'nd.topic_id')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId);
        $query = $this->getFilterByMediaQuery($query, $filterByMedia);
        $result = $query->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('t.name')
            ->orderBy('t.name')
            ->select(DB::raw('t.name, count(nd.id) as news'))
            ->get();

        return $result;
    }

    public function report3($from, $to, $clientId, $filterByMedia)
    {
        $q1 = DB::table('news_details as nd')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId);
        $q1 = $this->getFilterByMediaQuery($q1, $filterByMedia);
        $positiveNews = $q1->where('nd.tendency', '=', 1)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->select(DB::raw('count(nd.id) as positive'))
            ->first();

        $q2 = DB::table('news_details as nd')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId);
        $q2 = $this->getFilterByMediaQuery($q2, $filterByMedia);
        $negativeNews = $q2->where('nd.tendency', '=', 2)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->select(DB::raw('count(nd.id) as negative'))
            ->first();

        $q3 = DB::table('news_details as nd')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId);
        $q3 = $this->getFilterByMediaQuery($q3, $filterByMedia);
        $neutralNews = $q3->where('nd.tendency', '=', 3)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->select(DB::raw('count(nd.id) as neutral'))
            ->first();

        $client = Client::findOrFail($clientId);

        return [
            'client' => $client->name,
            'positive' => $positiveNews->positive,
            'negative' => $negativeNews->negative,
            'neutral' => $neutralNews->neutral,
        ];
    }

    public function report4($from, $to, $clientId, $filterByMedia)
    {
        $query = DB::table('news_details as nd')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId);
        $query = $this->getFilterByMediaQuery($query, $filterByMedia);
        $result = $query->whereRaw('ifnull(length(nd.gender), 0) > 0')
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('nd.gender')
            ->select(DB::raw('nd.gender, count(nd.gender) as news'))
            ->get();

        return$result;
    }

    // ASK FOR THIS REPORT as no source field is enabled for radio, tv, press
    // how will we know how to associate it to radio, tv or press
    public function report5($from, $to, $clientId, $filterByMedia)
    {
        $query = DB::table('news_details as nd')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', $clientId);
        $query = $this->getFilterByMediaQuery($query, $filterByMedia);
        $result = $query->whereRaw('ifnull(length(nd.source), 0) > 0')
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('nd.source')
            ->select(DB::raw('nd.source, count(nd.source) as news'))
            ->get();

        return$result;
    }

    public function report6($from, $to, $clientId, $filterByMedia)
    {
        $q1 = DB::table('media as m')
            ->join('news_details as nd', 'm.id','=','nd.media_id')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId);
        $q1 = $this->getFilterByMediaQuery($q1, $filterByMedia);
        $positiveNews = $q1->where('tendency', '=', 1)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('m.name')
            ->orderBy('m.name')
            ->select(DB::raw('m.name, count(nd.id) as positive'))
            ->get();

        $q2 = DB::table('media as m')
            ->join('news_details as nd', 'm.id','=','nd.media_id')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId);
        $q2 = $this->getFilterByMediaQuery($q2, $filterByMedia);
        $negativeNews = $q2->where('tendency', '=', 2)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('m.name')
            ->orderBy('m.name')
            ->select(DB::raw('m.name, count(nd.id) as negative'))
            ->get();

        $q3 = DB::table('media as m')
            ->join('news_details as nd', 'm.id','=','nd.media_id')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId);
        $q3 = $this->getFilterByMediaQuery($q3, $filterByMedia);
        $neutralNews = $q3->where('tendency', '=', 3)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('m.name')
            ->orderBy('m.name')
            ->select(DB::raw('m.name, count(nd.id) as neutral'))
            ->get();

        $result = [];
        foreach($positiveNews as $news) {
            if (!isset($result[$news->name])) {
                $result[$news->name] = [];
                $result[$news->name]['positive'] = $news->positive;
                $result[$news->name]['negative'] = '0';
                $result[$news->name]['neutral'] = '0';
            } else {
                $result[$news->name]['positive'] = $news->positive;
            }
        }
        foreach($negativeNews as $news) {
            if (!isset($result[$news->name])) {
                $result[$news->name] = [];
                $result[$news->name]['positive'] = '0';
                $result[$news->name]['negative'] = $news->negative;
                $result[$news->name]['neutral'] = '0';
            } else {
                $result[$news->name]['negative'] = $news->negative;
            }
        }
        foreach($neutralNews as $news) {
            if (!isset($result[$news->name])) {
                $result[$news->name] = [];
                $result[$news->name]['positive'] = '0';
                $result[$news->name]['negative'] = '0';
                $result[$news->name]['neutral'] = $news->neutral;
            } else {
                $result[$news->name]['neutral'] = $news->neutral;
            }
        }

        return $result;
    }

    // ASK FOR THIS REPORT as no source field is enabled for radio, tv, press
    // how will we know how to associate it to radio, tv or press
    public function report7($from, $to)
    {
        $positiveNews = DB::table('news_details as nd')
            ->where('nd.tendency', '=', 1)
            ->whereRaw('not isnull(nd.source)')
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('nd.source')
            ->select(DB::raw('nd.source, count(nd.tendency) as positive'))
            ->get();

        $negativeNews = DB::table('news_details as nd')
            ->where('nd.tendency', '=', 2)
            ->whereRaw('not isnull(nd.source)')
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('nd.source')
            ->select(DB::raw('nd.source, count(nd.tendency) as negative'))
            ->get();

        $neutralNews = DB::table('news_details as nd')
            ->where('nd.tendency', '=', 3)
            ->whereRaw('not isnull(nd.source)')
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('nd.source')
            ->select(DB::raw('nd.source, count(nd.tendency) as neutral'))
            ->get();

        $result = [];
        foreach($positiveNews as $news) {
            if (!isset($result[$news->source])) {
                $result[$news->source] = [];
                $result[$news->source]['positive'] = $news->positive;
                $result[$news->source]['negative'] = '0';
                $result[$news->source]['neutral'] = '0';
            } else {
                $result[$news->source]['positive'] = $news->positive;
            }
        }
        foreach($negativeNews as $news) {
            if (!isset($result[$news->source])) {
                $result[$news->source] = [];
                $result[$news->source]['positive'] = '0';
                $result[$news->source]['negative'] = $news->negative;
                $result[$news->source]['neutral'] = '0';
            } else {
                $result[$news->source]['negative'] = $news->negative;
            }
        }
        foreach($neutralNews as $news) {
            if (!isset($result[$news->source])) {
                $result[$news->source] = [];
                $result[$news->source]['positive'] = '0';
                $result[$news->source]['negative'] = '0';
                $result[$news->source]['neutral'] = $news->neutral;
            } else {
                $result[$news->source]['neutral'] = $news->neutral;
            }
        }

        return $result;
    }
}
