<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicTechTipResource extends JsonResource
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
            'public' => $this->public,
            'subject' => $this->subject,
            'details' => $this->details,
            'created_at' => Carbon::parse($this->created_at)->format('M d, Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('M d, Y'),
            'public_href' => $this->public_href,
            'tech_tip_type' => $this->TechTipType,
        ];
    }
}
