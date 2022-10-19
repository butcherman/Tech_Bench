<?php

namespace App\Http\Requests\User;

use App\Models\UserRoles;
use Illuminate\Foundation\Http\FormRequest;

class UserRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('create', UserRoles::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'name'                  => 'required|string|unique:user_roles',
            'description'           => 'required|string',
        ];
    }
}
