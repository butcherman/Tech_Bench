<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class InitializeUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'email'    => 'required|exists:users',              //  Must be a valid email address
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
