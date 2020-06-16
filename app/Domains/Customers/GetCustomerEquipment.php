<?php

namespace App\Domains\Customers;

use App\Customers;
use App\CustomerSystems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetCustomerEquipment extends GetCustomerDetails
{
    public function execute($custID)
    {
        $equip = $this->getEquipment($custID);

        //  Get any equipment that is shared between sites
        if($parent = $this->getParentID($custID))
        {
            $equip = $equip->merge($this->getEquipment($parent, true));
        }

        return $equip;
    }

    protected function getEquipment($custID, $shared = false)
    {
        return CustomerSystems::where('cust_id', $custID)
            ->when($shared, function($q)
            {
                $q->where('shared', 1);
            })
            ->with('CustomerSystemData')
            ->get();
    }
}
