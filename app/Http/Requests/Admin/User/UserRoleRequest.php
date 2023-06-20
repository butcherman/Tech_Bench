<?php

namespace App\Http\Requests\Admin\User;

use App\Models\UserRolePermissions;
use App\Models\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        if ($this->user_role) {
            return $this->user()->can('update', $this->user_role);
        }

        return $this->user()->can('create', UserRoles::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('user_roles')->ignore($this->user_role),
            ],
            'description' => 'required|string',
        ];
    }

    /**
     * Build a new Role and assign permissions
     */
    public function processNewRole()
    {
        $newRole = UserRoles::create($this->only(['name', 'description']));
        $permList = $this->except(['name', 'description']);

        foreach ($permList as $perm => $value) {
            UserRolePermissions::create([
                'role_id' => $newRole->role_id,
                'perm_type_id' => str_replace('type-', '', $perm),
                'allow' => (bool) $value,
            ]);
        }

        return $newRole;
    }

    /**
     * Update an existing Role and modify permissions
     */
    public function updateExistingRole()
    {
        $this->user_role->update($this->only(['name', 'description']));
        $permList = $this->except(['name', 'description']);

        foreach ($permList as $perm => $value) {
            UserRolePermissions::where('role_id', $this->user_role->role_id)
                ->where('perm_type_id', str_replace('type-', '', $perm))
                ->update([
                    'allow' => $value,
                ]);
        }
    }
}
