<?php

namespace App\Http\Requests\Init;

use Illuminate\Foundation\Http\FormRequest;

class UserSecurityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'password.expire' => ['required', 'numeric'],
            'password.min_length' => ['required', 'numeric'],
            'password.contains_uppercase' => ['required', 'boolean'],
            'password.contains_lowercase' => ['required', 'boolean'],
            'password.contains_number' => ['required', 'boolean'],
            'password.contains_special' => ['required', 'boolean'],
            'password.disable_compromised' => ['required', 'boolean'],
            'twoFa.enabled' => ['required', 'boolean'],
            'twoFa.required' => ['required', 'boolean'],
            'twoFa.allow_save_device' => ['required', 'boolean'],
            'twoFa.methods.email' => ['required', 'boolean'],
            'twoFa.methods.authenticator' => ['required', 'boolean'],
        ];
    }
}
