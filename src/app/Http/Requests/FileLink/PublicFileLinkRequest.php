<?php

namespace App\Http\Requests\FileLink;

use Illuminate\Foundation\Http\FormRequest;

class PublicFileLinkRequest extends FormRequest
{
    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->link->validatePublicLink();
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
