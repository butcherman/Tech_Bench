<?php

namespace App\Http\Requests\Admin\Config;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class BasicSettingsRequest extends FormRequest
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
            'url' => ['required', 'string'],
            'company_name' => ['required', 'string'],
            'timezone' => ['required', 'string'],
            'max_filesize' => ['required', 'numeric'],
            'welcome_message' => ['nullable', 'string'],
        ];
    }
}
