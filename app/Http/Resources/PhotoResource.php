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
            'image' => $this->image,
            'caption' => $this->caption,
            'notes' => $this->notes,
            'album' => $this->album,
            'tags' => $this->tags,
            'created_date' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_date' => Carbon::parse($this->updated_at)->toDateTimeString(),
            'created_ago' => Carbon::parse($this->created_at)->diffForHumans(Carbon::now()),
            'time_string_created' => Carbon::parse($this->created_at)->toFormattedDateString(),
            'time_string_updated' => Carbon::parse($this->updated_at)->toFormattedDateString()
        ];
    }
}
