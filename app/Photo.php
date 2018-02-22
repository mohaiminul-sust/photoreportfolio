<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
  
    protected $fillable = ['album_id', 'caption', 'notes', 'image'];

    public function album()
    {
        return $this->belongsTo('App\Album');
    }

    public function tags(){
        return $this->hasMany('App\Tag');
    }
}
