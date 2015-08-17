<?php

class News extends Eloquent {
    protected $table = 'news';
    protected $hidden = ['created_at', 'updated_at'];

    public function details()
    {
        return $this->hasMany('NewsDetail');
    }

    public function client()
    {
        return $this->belongsTo('Client');
    }
}

