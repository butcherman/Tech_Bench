<?php

namespace App\Http\Requests\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->customer) {
            return $this->user()->can('update', $this->customer);
        }

        return $this->user()->can('create', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->customer) {
            return [
                'name' => ['required', 'string'],
                'dba_name' => ['nullable', 'string'],
                'primary_site_id' => [
                    'required',
                    'exists:customer_sites,cust_site_id',
                ],
            ];
        }

        return [
            'cust_id' => ['nullable', 'numeric'],
            'name' => ['required', 'string'],
            'dba_name' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip' => ['required', 'numeric'],
        ];
    }
}
