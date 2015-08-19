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

    public function urls()
    {
        return $this->hasMany('NewsUrl');
    }

    public function uploads()
    {
        return $this->hasMany('NewsUpload');
    }

    public function getDateAttribute($value)
    {
        if(gettype($value) === 'string') {
            $date = new DateTime($value);
            return $date->format('d/m/Y');
        }
        return $value;
    }
}

