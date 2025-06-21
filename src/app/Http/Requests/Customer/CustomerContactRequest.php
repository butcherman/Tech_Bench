<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerContact;
use Illuminate\Foundation\Http\FormRequest;

class CustomerContactRequest extends FormRequest
{
    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->contact) {
            return $this->user()->can('update', $this->contact);
        }

        return $this->user()->can('create', CustomerContact::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'title' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'site_list' => ['nullable'],
            'local' => ['required', 'boolean'],
            'decision_maker' => ['required', 'boolean'],
            'note' => ['nullable', 'string'],
            'phones' => ['nullable', 'array'],
        ];
    }
}
