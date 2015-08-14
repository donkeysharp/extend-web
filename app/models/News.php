<?php

class News extends Eloquent {
    protected $table = 'news';
    protected $hidden = ['created_at', 'updated_at'];
}
