<?php

// TODO - Refactor

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileLinkTableResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->link_name,
            'expires' => Carbon::parse($this->expire)->format('M d, Y'),
            'allow_upload' => $this->allow_upload,
            'has_instructions' => $this->instructions ? true : false,
        ];
    }
}
