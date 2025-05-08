<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class DisableCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->site) {
            return $this->user()->can('delete', $this->site);
        }

        return $this->user()->can('delete', $this->customer);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // TODO - Cannot disable if it is the only site
        return [
            'reason' => ['required', 'string'],
        ];
    }
}
