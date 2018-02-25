<?php

namespace App;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{   
    use Eloquence;
    protected $table = 'tags';
  
    protected $guarded = [];
    protected $searchableColumns = ['tag'];

    public function photo()
    {
        return $this->belongsTo('App\Photo');
    }
}
