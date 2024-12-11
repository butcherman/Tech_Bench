<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileLinkAdministrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'link_id' => $this->link_id,
            'link_name' => $this->link_name,
            'expire' => Carbon::parse($this->expire)->format('M d, Y'),
            'is_expired' => $this->is_expired,
            'created_by' => $this->User->full_name,
            'href' => route('admin.links.manage.show', $this->link_id),
        ];
    }
}
