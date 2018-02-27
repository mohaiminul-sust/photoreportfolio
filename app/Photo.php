<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
  
    protected $guarded = [];

    public function album()
    {
        return $this->belongsTo('App\Album');
    }

    public function tags(){
        return $this->hasMany('App\Tag');
    }

    public function scopeSearchByKeyword($query, $keyword)
    {
        if ($keyword != '') {
            $query->where(function ($query) use ($keyword) {
                $query->where("caption", "LIKE","%$keyword%");
            });
        }
        return $query;
    }
}
