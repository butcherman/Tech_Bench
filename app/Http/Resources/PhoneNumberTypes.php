<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneNumberTypes extends JsonResource
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
            'value' => $this->phone_type_id,
            'text'  => $this->description,
        ];
    }
}
