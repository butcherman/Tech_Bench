<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechTipResource extends JsonResource
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
            'sticky' => $this->sticky,
            'public' => $this->public,
            'allow_comments' => $this->allow_comments,
            'subject' => $this->subject,
            'slug' => $this->slug,
            'details' => $this->details,
            'created_at' => Carbon::parse($this->created_at)->format('M d, Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('M d, Y'),
            'href' => $this->href,
            'public_href' => $this->public_href,
            'created_by' => [
                'full_name' => $this->CreatedBy->full_name,
            ],
            'updated_by' => [
                'full_name' => $this->UpdatedBy->full_name,
            ],
            'tech_tip_type' => $this->TechTipType,
        ];
    }
}
