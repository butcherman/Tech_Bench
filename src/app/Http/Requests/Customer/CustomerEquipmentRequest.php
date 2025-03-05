<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerEquipment;
use App\Rules\CheckForDuplicateSiteEquipment;
use Illuminate\Foundation\Http\FormRequest;

class CustomerEquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->equipment) {
            return $this->user()->can('update', $this->equipment);
        }

        return $this->user()->can('create', CustomerEquipment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->equipment) {
            return [
                'site_list' => [
                    'nullable',
                    'array',
                ],
            ];
        }

        return [
            'equip_id' => ['required', 'exists:equipment_types'],
            'site_list' => [
                'required',
                'array',
                new CheckForDuplicateSiteEquipment,
            ],
        ];
    }
}
