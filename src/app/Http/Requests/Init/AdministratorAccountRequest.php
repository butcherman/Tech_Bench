<?php

namespace App\Http\Requests\Init;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class AdministratorAccountRequest extends FormRequest
{
    use PasswordValidationRules;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => ['required'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'role_id' => ['required', 'exists:user_roles'],
            'password' => $this->tmpPasswordRules(
                session()->get('setup.security')
            ),
        ];
    }
}
