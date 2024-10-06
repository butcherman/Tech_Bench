<?php

// TODO - Refactor

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class SecurityRequest extends FormRequest
{
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
            'wildcard' => 'required|boolean',
            'certificate' => 'required|string',
            'key' => 'required_if:wildcard,true',
            'intermediate' => 'required|string',
        ];
    }
}
