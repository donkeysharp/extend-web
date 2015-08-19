<?php

class Client extends Eloquent
{
    public function contacts()
    {
        return $this->hasMany('Contact');
    }
}
