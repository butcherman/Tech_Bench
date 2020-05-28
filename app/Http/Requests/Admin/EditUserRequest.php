<?php

namespace App\Http\Requests\Admin;

use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
            'user_id'    => 'required|exists:users',
            'role_id'    => 'required|numeric|exists:user_role_types,role_id',
            'username'   => [
                'required',
                'regex:/^[a-zA-Z0-9_]*$/',
                Rule::unique('users')->ignore(User::findOrFail($this->request->get('user_id'))),
            ],
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => [
                'required',
                'email',
                Rule::unique('users')->ignore(User::findOrFail($this->request->get('user_id'))),
            ],
        ];
    }
}
