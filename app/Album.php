<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'albums';

    protected $guarded = [];

    public function Photos(){
        return $this->hasMany('App\Photo');
    }
}
