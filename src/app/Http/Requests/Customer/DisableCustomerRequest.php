<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
        return [
            'reason' => ['required', 'string'],
        ];
    }

    /**
     * If this is a site, and marked as the primary site or the only site,
     * validation will fail.
     */
    public function after(): array
    {
        if (! $this->site) {
            return [];
        }

        return [
            function (Validator $validator) {
                if ($this->site->Customer->site_count === 1) {
                    $validator->errors()
                        ->add('reason', 'Cannot disable the only Customer Site');
                }

                if ($this->site->is_primary) {
                    $validator->errors()
                        ->add('reason', 'Cannot disable the Primary Site');
                }
            },
        ];
    }
}
