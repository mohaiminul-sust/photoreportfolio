<?php

namespace App;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use Eloquence;
    protected $table = 'photos';
  
    protected $guarded = [];
    protected $searchableColumns = ['caption', 'notes', 'album.name', 'tags.tag'];

    public function album()
    {
        return $this->belongsTo('App\Album');
    }

    public function tags(){
        return $this->hasMany('App\Tag');
    }
}
