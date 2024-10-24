<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'status' => $this->deleted_at ? false : true,
            'role_name' => $this->UserRole->name,
            'role_description' => $this->UserRole->description,
            'permissions' => $this->UserRole->UserRolePermission,
        ];
    }
}
