<?php

namespace App\Http\Requests\Admin\Config;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class EmailSettingsRequest extends FormRequest
{
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
            'from_address' => ['required', 'email'],
            'username' => ['required_if:require_auth,true'],
            'password' => ['required_if:require_auth,true'],
            'host' => ['required', 'string'],
            'port' => ['required', 'numeric'],
            'encryption' => ['required', 'string'],
            'require_auth' => ['required', 'boolean'],
        ];
    }

    /**
     * Remove the username and password fields if Authentication is not needed
     */
    public function passedValidation(): void
    {
        if (! $this->require_auth) {
            $this->request->remove('username');
            $this->request->remove('password');
        }
    }
}
