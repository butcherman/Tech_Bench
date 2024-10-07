<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;

class EmailSettingsRequest extends FormRequest
{
    use AppSettingsTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request
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
}
