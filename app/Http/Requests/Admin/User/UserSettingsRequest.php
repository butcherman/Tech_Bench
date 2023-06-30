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
            'allow_login' => 'required|boolean',
            'allow_register' => 'required|boolean',
            'tenant' => 'required_if:allowOath,true',
            'client_id' => 'required_if:allowOath,true',
            'client_secret' => 'required_if:allowOath,true',
            'redirectUri' => 'required_if:allowOath,true',
        ];
    }
}
