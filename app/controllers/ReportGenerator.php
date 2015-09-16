<?php

class ReportGenerator
{
    public function report1($from, $to)
    {
        $result = DB::table('media as m')
            ->join('news_details as nd', 'm.id', '=', 'nd.media_id')
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('m.name')
            ->orderBy('m.name')
            ->select(DB::raw('m.name, count(nd.id) as news'))
            ->get();

        return $result;
    }

    public function report2($from, $to)
    {
        $result = DB::table('topics as t')
            ->join('news_details as nd', 't.id', '=', 'nd.topic_id')
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('t.name')
            ->orderBy('t.name')
            ->select(DB::raw('t.name, count(nd.id) as news'))
            ->get();

        return $result;
    }

    public function report3($from, $to, $clientId)
    {
        $positiveNews = DB::table('news_details as nd')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId)
            ->where('nd.tendency', '=', 1)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->select(DB::raw('count(nd.id) as positive'))
            ->first();

        $negativeNews = DB::table('news_details as nd')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId)
            ->where('nd.tendency', '=', 2)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->select(DB::raw('count(nd.id) as negative'))
            ->first();

        $neutralNews = DB::table('news_details as nd')
            ->join('news as n', 'n.id', '=', 'nd.news_id')
            ->where('n.client_id', '=', $clientId)
            ->where('nd.tendency', '=', 3)
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

    public function report4($from, $to)
    {
        $result = DB::table('news_details as nd')
            ->whereRaw('ifnull(length(nd.gender), 0) > 0')
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('nd.gender')
            ->select(DB::raw('nd.gender, count(nd.gender) as news'))
            ->get();

        return$result;
    }

    public function report5($from, $to)
    {
        $result = DB::table('news_details as nd')
            ->whereRaw('ifnull(length(nd.source), 0) > 0')
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('nd.source')
            ->select(DB::raw('nd.source, count(nd.source) as news'))
            ->get();

        return$result;
    }

    public function report6($from, $to)
    {
        $positiveNews = DB::table('media as m')
            ->join('news_details as nd', 'm.id','=','nd.media_id')
            ->where('tendency', '=', 1)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('m.name')
            ->orderBy('m.name')
            ->select(DB::raw('m.name, count(nd.id) as positive'))
            ->get();

        $negativeNews = DB::table('media as m')
            ->join('news_details as nd', 'm.id','=','nd.media_id')
            ->where('tendency', '=', 2)
            ->where('nd.created_at', '>=', $from)
            ->where('nd.created_at', '<=', $to)
            ->groupBy('m.name')
            ->orderBy('m.name')
            ->select(DB::raw('m.name, count(nd.id) as negative'))
            ->get();

        $neutralNews = DB::table('media as m')
            ->join('news_details as nd', 'm.id','=','nd.media_id')
            ->where('tendency', '=', 3)
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
