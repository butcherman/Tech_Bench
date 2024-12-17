<?php

namespace App\Http\Resources\Reports\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @codeCoverageIgnore
 */
class UserPermissionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
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
