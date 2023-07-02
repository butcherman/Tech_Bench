<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage', User::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'twoFa.required' => 'required|boolean',
            'twoFa.allow_save_device' => 'required|boolean',
            'twoFa.allow_via_email' => 'required|boolean',
            'twoFa.allow_via_sms' => 'required|boolean',
            'oath.allow_login' => 'required|boolean',
            'oath.allow_register' => 'required|boolean',
            'oath.tenant' => 'required_if:allowOath,true',
            'oath.client_id' => 'required_if:allowOath,true',
            'oath.client_secret' => 'required_if:allowOath,true',
            'oath.redirectUri' => 'required_if:allowOath,true',
        ];
    }
}
