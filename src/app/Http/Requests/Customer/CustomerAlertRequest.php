<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerAlert;
use Illuminate\Foundation\Http\FormRequest;

class CustomerAlertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->alert) {
            return $this->user()->can('update', $this->alert);
        }

        return $this->user()->can('create', CustomerAlert::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'type' => 'required|string',
        ];
    }
}
