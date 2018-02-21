<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
  
    protected $fillable = ['photo_id', 'tag'];

    public function photo()
    {
        return $this->belongsTo('App\Photo');
    }
}
