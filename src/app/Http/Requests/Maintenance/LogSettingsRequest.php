<?php

namespace App\Http\Requests\Maintenance;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class LogSettingsRequest extends FormRequest
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
            'days' => ['required', 'numeric'],
            'log_level' => ['required', 'string'],
        ];
    }
}
