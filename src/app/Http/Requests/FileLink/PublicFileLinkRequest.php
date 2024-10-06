<?php

// TODO - Refactor

namespace App\Http\Requests\FileLink;

use Illuminate\Foundation\Http\FormRequest;

class PublicFileLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'notes' => 'nullable|string',
        ];
    }
}
