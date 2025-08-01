<?php

namespace App\Http\Requests\Admin\User;

use App\Models\AppSettings;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserSettingsRequest extends FormRequest
{
    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'auto_logout_timer' => ['required'],
            'twoFa.required' => ['required', 'boolean'],
            'twoFa.allow_save_device' => ['required', 'boolean'],
            'twoFa.allow_via_email' => ['required', 'boolean'],
            'twoFa.allow_via_authenticator' => ['required', 'boolean'],
            'oath.allow_login' => ['required', 'boolean'],
            'oath.allow_register' => ['required', 'boolean'],
            'oath.tenant' => ['required_if:oath.allow_login,true'],
            'oath.client_id' => ['required_if:oath.allow_login,true'],
            'oath.client_secret' => ['required_if:oath.allow_login,true'],
            'oath.secret_expires' => ['required_if:oath.allow_login,true'],
            'oath.redirect' => ['required_if:oath.allow_login,true'],
            'oath.default_role_id' => ['required_if:oath.allow_register,true'],
        ];
    }
}
