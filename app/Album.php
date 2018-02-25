<?php

namespace App;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use Eloquence;

    protected $table = 'albums';

    protected $guarded = [];
    protected $searchableColumns = ['name', 'description'];

    public function photos(){
        return $this->hasMany('App\Photo');
    }
}
