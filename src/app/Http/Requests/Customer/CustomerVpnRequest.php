<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerEquipment;
use Illuminate\Foundation\Http\FormRequest;

class CustomerVpnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->vpn_datum) {
            return $this->user()->can('update', CustomerEquipment::class);
        }

        return $this->user()->can('create', CustomerEquipment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'vpn_client_name' => ['required', 'string'],
            'vpn_portal_url' => ['required', 'string'],
            'vpn_username' => ['nullable', 'string'],
            'vpn_password' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
