<?php

namespace App\Rules;

use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CheckForDuplicateSiteEquipment implements ValidationRule, DataAwareRule
{
    protected $data;

    /**
     * For the sites selected, verify that none of the sites currently have the
     * selected equipment.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Get the customer instance and see if they have the equipment being assigned
        $customer = CustomerSite::find($value[0])->Customer;
        $equip = CustomerEquipment::where('cust_id', $customer->cust_id)
            ->where('equip_id', $this->data['equip_id'])
            ->first();
        if ($equip) {
            foreach ($value as $siteId) {
                $siteEquip = DB::table('customer_site_equipment')
                    ->where('cust_site_id', $siteId)
                    ->where('cust_equip_id', $equip->cust_equip_id)
                    ->count();
                if ($siteEquip) {
                    $site = CustomerSite::find($siteId);

                    $fail($site->site_name . ' already has this type of equipment assigned.');
                }
            }
        }
    }

    /**
     * Include the other Form Entries
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
