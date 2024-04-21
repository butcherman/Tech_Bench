<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
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
     * Get the validation rules that apply to the request
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
            'equip_id' => 'required|exists:equipment_types',
            'site_list' => [
                'required',
                'array',
                new CheckForDuplicateSiteEquipment,
            ],
        ];
    }

    /**
     * Create a new Equipment resource and assign it to the Customer Sites
     */
    public function createEquipment()
    {
        $newEquipment = CustomerEquipment::create([
            'cust_id' => $this->customer->cust_id,
            'equip_id' => $this->equip_id,
        ]);

        /**
         * Attach the equipment to the necessary sites
         */
        foreach ($this->site_list as $site) {
            $site = CustomerSite::find($site);
            $newEquipment->CustomerSite()->attach($site);
        }

        $newEquipment->save();

        return $newEquipment;
    }
}
