<?php

namespace App\Http\Requests\User;

use App\Models\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        if ($this->role) {
            return $this->user()->can('update', $this->role);
        }

        return $this->user()->can('create', UserRoles::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('user_roles')->ignore($this->role),
            ],
            'description' => 'required|string',
        ];
    }
}
