<?php

namespace App\Http\Resources\Reports\Users;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSummaryResource extends JsonResource
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
            'username' => $this->username,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'role_name' => $this->UserRole->name,
            'created_at' => Carbon::parse($this->created_at)->format('M d, Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('M d, Y'),
            'deleted_at' => $this->when(
                $this->deleted_at,
                Carbon::parse($this->deleted_at)->format('M d, Y')
            ),
            'password_expires' => $this->when(
                $this->password_expires,
                Carbon::parse($this->password_expires)->format('M d, Y')
            ),
            'last_login' => $this->getLastLogin(),
        ];
    }
}
