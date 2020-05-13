<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|unique:users',
        ];
    }

}
