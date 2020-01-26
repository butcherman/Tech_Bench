<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerFileTypes extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'value' => $this->file_type_id,
            'text'  => $this->description,
        ];
    }
}
