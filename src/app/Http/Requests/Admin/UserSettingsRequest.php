<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserSettingsRequest extends FormRequest
{
    use AppSettingsTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'twoFa.required' => 'required|boolean',
            'twoFa.allow_save_device' => 'required|boolean',
            'oath.allow_login' => 'required|boolean',
            'oath.allow_register' => 'required|boolean',
            'oath.allow_bypass_2fa' => 'required|boolean',
            'oath.tenant' => 'required_if:oath.allow_login,true',
            'oath.client_id' => 'required_if:oath.allow_login,true',
            'oath.client_secret' => 'required_if:oath.allow_login,true',
            'oath.secret_expires' => 'required_if:oath.allow_login,true',
            'oath.redirectUri' => 'required_if:oath.allow_login,true',
        ];
    }

    /**
     * Save the new App Settings
     */
    public function updateUserSettings()
    {
        $this->saveSettingsArray($this->oath, 'services.azure');
        $this->saveSettingsArray($this->twoFa, 'auth.twoFa');
    }
}
