<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'current_password' => 'required|current_password',
            'password'         => 'required|confirmed|min:6|different:current_password',
        ];
    }
}
