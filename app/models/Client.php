<?php

class Client extends Eloquent
{
    public function contacts()
    {
        return $this->hasMany('Contact');
    }

    public function customSubtitles()
    {
        return $this->belongsToMany('Subtitle', 'custom_subtitles');
    }
}
