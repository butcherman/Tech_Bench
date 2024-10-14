<?php

namespace App\Http\Requests\Admin\User;

use App\Models\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user_role) {
            return $this->user()->can('update', $this->user_role);
        }

        return $this->user()->can('create', UserRole::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('user_roles')->ignore($this->user_role),
            ],
            'description' => ['required', 'string'],
            'permissions' => ['required', 'array'],
        ];
    }
}
