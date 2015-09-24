<?php

class NewsDetail extends Eloquent
{
    protected $table = 'news_details';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    const PRINTED = 1;
    const DIGITAL = 2;
    const RADIO = 3;
    const TV = 4;
    const SOURCE = 5;

    public function media()
    {
        return $this->belongsTo('Media');
    }

    public function topic()
    {
        return $this->belongsTo('Topic');
    }

    public function news()
    {
        return $this->belongsTo('News');
    }

    public static function createInstance($type, $data, $newsId)
    {
        if ($type == NewsDetail::PRINTED) {
            return static::createPrintedInstance($data, $newsId);
        } else if($type == NewsDetail::DIGITAL) {
            return static::createDigitalInstance($data, $newsId);
        } else if($type == NewsDetail::RADIO) {
            return static::createRadioInstance($data, $newsId);
        } else if($type == NewsDetail::TV) {
            return static::createTvInstance($data, $newsId);
        } else if ($type == NewsDetail::SOURCE) {
            return static::createSourceInstance($data, $newsId);
        }
        return null;
    }

    public static function createPrintedInstance($data, $newsId)
    {
        $item = new NewsDetail();
        $item->type = NewsDetail::PRINTED;
        $item->news_id = $newsId;
        $item->media_id = $data['media_id'];
        $item->id = $data['id'];
        $item->section = $data['section'];
        $item->page = $data['page'];
        $item->title = $data['title'];
        $item->subtitle = $data['subtitle'];
        $item->gender = $data['gender'];
        $item->topic_id = $data['topic_id'];
        $item->measure = $data['measure'];
        $item->cost = $data['cost'];
        $item->tendency = $data['tendency'];
        $item->description = $data['description'];
        $item->source = $data['source'];
        $item->alias = $data['alias'];

        return $item;
    }

    public static function createDigitalInstance($data, $newsId)
    {
        $item = new NewsDetail();
        $item->type = NewsDetail::DIGITAL;
        $item->news_id = $newsId;
        $item->media_id = $data['media_id'];
        $item->section = $data['section'];
        $item->page = $data['page'];
        $item->subtitle = $data['subtitle'];
        $item->title = $data['title'];
        $item->web = $data['web'];
        $item->gender = $data['gender'];
        $item->topic_id = $data['topic_id'];
        $item->measure = $data['measure'];
        $item->cost = $data['cost'];
        $item->tendency = $data['tendency'];
        $item->description = $data['description'];
        $item->source = $data['source'];
        $item->alias = $data['alias'];

        return $item;
    }

    public static function createRadioInstance($data, $newsId)
    {
        $item = new NewsDetail();
        $item->type = NewsDetail::RADIO;
        $item->news_id = $newsId;
        $item->media_id = $data['media_id'];
        $item->source = $data['source'];
        $item->subtitle = $data['subtitle'];
        $item->alias = $data['alias'];
        $item->title = $data['title'];
        $item->communication_risk = $data['communication_risk'];
        $item->show = $data['show'];
        $item->topic_id = $data['topic_id'];
        $item->measure = $data['measure'];
        $item->cost = $data['cost'];
        $item->tendency = $data['tendency'];
        $item->description = $data['description'];

        return $item;
    }

    public static function createTvInstance($data, $newsId)
    {
        $item = new NewsDetail();
        $item->type = NewsDetail::TV;
        $item->news_id = $newsId;
        $item->media_id = $data['media_id'];
        $item->source = $data['source'];
        $item->subtitle = $data['subtitle'];
        $item->alias = $data['alias'];
        $item->title = $data['title'];
        $item->communication_risk = $data['communication_risk'];
        $item->show = $data['show'];
        $item->topic_id = $data['topic_id'];
        $item->measure = $data['measure'];
        $item->cost = $data['cost'];
        $item->tendency = $data['tendency'];
        $item->description = $data['description'];

        return $item;
    }

    public static function createSourceInstance($data, $newsId)
    {
        $item = new NewsDetail();
        $item->type = NewsDetail::SOURCE;
        $item->news_id = $newsId;
        $item->media_id = $data['media_id'];
        $item->title = $data['title'];
        $item->subtitle = $data['subtitle'];
        $item->source = $data['source'];
        $item->alias = $data['alias'];
        $item->topic_id = $data['topic_id'];
        $item->measure = $data['measure'];
        $item->cost = $data['cost'];
        $item->tendency = $data['tendency'];
        $item->description = $data['description'];

        return $item;
    }

}
