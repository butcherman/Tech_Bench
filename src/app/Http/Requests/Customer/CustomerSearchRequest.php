<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'basic' => ['required', 'boolean'],
            'page' => ['required_if:basic,false', 'numeric'],
            'perPage' => ['required_if:basic,false', 'numeric'],
            'searchFor' => ['nullable', 'string'],
        ];
    }
}
