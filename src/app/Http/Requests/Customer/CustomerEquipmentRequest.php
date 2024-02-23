<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use Illuminate\Foundation\Http\FormRequest;

class CustomerEquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', CustomerEquipment::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'equip_id' => 'required|exists:equipment_types',
            'site_list' => 'required|array',
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
