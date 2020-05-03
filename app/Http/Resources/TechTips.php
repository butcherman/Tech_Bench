<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TechTips extends JsonResource
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
            'tip_id'       => $this->tip_id,
            'sticky'       => $this->sticky,
            'subject'      => $this->subject,
            'description'  => Str::words($this->description, 50),
            'created_at'   => Carbon::parse($this->created_at)->format('M d, Y'),
            'system_types' => $this->SystemTypes,
        ];
    }
}
