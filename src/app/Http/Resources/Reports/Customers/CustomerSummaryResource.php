<?php

namespace App\Http\Resources\Reports\Customers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'customer_id' => $this->cust_id,
            'sites' => $this->CustomerSite->count(),
            'equipment_assigned' => $this->CustomerEquipment->count(),
            'notes' => $this->CustomerNote->count(),
            'contacts' => $this->CustomerContact->count(),
            'files' => $this->CustomerFile->count(),
        ];
    }
}
