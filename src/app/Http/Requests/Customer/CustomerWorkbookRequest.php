<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerWorkbookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO - Authorize guest users
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'index' => ['required', 'string'],
            'fieldValue' => ['nullable', 'string'],
            'can_publish' => ['required', 'boolean'],
        ];
    }
}
