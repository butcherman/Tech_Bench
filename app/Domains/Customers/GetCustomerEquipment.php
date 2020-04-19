<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Customers;
use App\CustomerSystems;

class GetCustomerEquipment
{
    protected $custID;

    public function __construct($custID)
    {
        $this->custID = $custID;
    }

    //  Get all equipment types that are assigned to the customer
    public function execute()
    {
        $hasParent = Customers::findOrFail($this->custID)->parent_id;
        $localEquip = $this->getLocalEquipment();

        if($hasParent)
        {
            $parentEquip = $this->getParentEquipment($hasParent);
            return $localEquip->merge($parentEquip);
        }
        return $localEquip;
    }

    //  Retrieve any equipment attached to the customer
    protected function getLocalEquipment()
    {
        $equipList = CustomerSystems::where('cust_id', $this->custID)
                        ->with('CustomerSystemData')
                        ->get();

        Log::debug('Customer Equipment Query completed for customer ID '.$this->custID.'.  Results - ', array($equipList));
        return $equipList;
    }

    //  Retrieve any equipment attached to the parent customer
    protected function getParentEquipment($parentID)
    {
        $parentList = CustomerSystems::where('cust_id', $parentID)
                        ->with('CustomerSystemData')
                        ->get();

        Log::debug('Customer Parent Equipment Query completed for Customer ID '.$this->custID.'.  Results - ', array($parentList));
        return $parentList;
    }
}
