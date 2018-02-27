<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class AlbumResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'cover_image' => url('/').$this->cover_image,
            'photos' => $this->photos,
            'created_date' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_date' => Carbon::parse($this->updated_at)->toDateTimeString(),
            'created_ago' => Carbon::parse($this->created_at)->diffForHumans(Carbon::now()),
            'updated_ago' => Carbon::parse($this->updated_at)->diffForHumans(Carbon::now())
        ];
    }
}
