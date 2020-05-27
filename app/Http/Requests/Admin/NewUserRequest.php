<?php

namespace App\Http\Requests\Admin;

use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;

class NewUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('hasAccess', 'Manage Users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id'    => 'required|numeric|exists:user_role_types,role_id',
            'username'   => 'required|unique:users|regex:/^[a-zA-Z0-9_]*$/',
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:users',
        ];
    }
}
