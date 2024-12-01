<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicSearchTechTipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tip_id' => $this->tip_id,
            'subject' => $this->subject,
            'details' => $this->details,
            'updated_at' => Carbon::parse($this->updated_at)->format('M d, Y'),
            'href' => $this->public_href,
        ];
    }
}
