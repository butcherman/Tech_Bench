<?php

namespace App\Http\Requests;

use App\Rules\ValidatePassword;

use Illuminate\Foundation\Http\FormRequest;

class UserChangePasswordRequest extends FormRequest
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
            'oldPass' => ['required', new ValidatePassword],
            'newPass' => 'required|string|min:6|confirmed|different:oldPass'
        ];
    }
}