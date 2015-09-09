<?php

class Bulletin extends Eloquent
{
    protected $table = 'bulletins';

    public function details()
    {
        return $this->belongsToMany('NewsDetail', 'bulletin_news_detail');
    }

    public function client()
    {
        return $this->belongsTo('Client');
    }
}
