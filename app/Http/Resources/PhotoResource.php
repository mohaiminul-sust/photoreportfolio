<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class PhotoResource extends Resource
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
            'image' => url('/').$this->image,
            'caption' => $this->caption,
            'notes' => $this->notes,
            'album' => $this->album,
            'tags' => $this->tags,
            'exif' => \Image::make(public_path().$this->image)->exif(),
            'created_date' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_date' => Carbon::parse($this->updated_at)->toDateTimeString(),
            'created_ago' => Carbon::parse($this->created_at)->diffForHumans(Carbon::now()),
            'updated_ago' => Carbon::parse($this->updated_at)->diffForHumans(Carbon::now())
        ];
    }
}
