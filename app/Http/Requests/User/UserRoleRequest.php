<?php

namespace App\Http\Requests\User;

use App\Models\UserRolePermissions;

use Illuminate\Foundation\Http\FormRequest;

class UserRoleRequest extends FormRequest
{
    /**
     * Only users with "Manage Roles" permissions can submit this request
     */
    public function authorize()
    {
        return $this->user()->can('create', UserRolePermissions::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'                  => 'required|string',
            'description'           => 'required|string',
            'user_role_permissions' => 'required|array',
        ];
    }
}
