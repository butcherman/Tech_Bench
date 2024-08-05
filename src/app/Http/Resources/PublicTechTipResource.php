<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicTechTipResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'href' => $this->public_href,
            'subject' => $this->subject,
            'slug' => $this->slug,
            'updated_at' => Carbon::parse($this->updated_at)
                ->format('M d, Y'),
        ];
    }
}
