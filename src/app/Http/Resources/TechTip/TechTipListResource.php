<?php

namespace App\Http\Resources\TechTip;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechTipListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->subject,
            'href' => route('tech-tips.show', $this->slug),
        ];
    }
}
