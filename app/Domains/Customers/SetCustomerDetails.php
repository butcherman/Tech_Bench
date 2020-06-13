<?php

namespace App\Domains\Customers;

use App\Customers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class SetCustomerDetails
{
    public function createCustomer($request)
    {
        if($request->parent_id != null)
        {
            $request->parent_id = $this->validateParentID($request->parent_id);
        }

        $newCust = Customers::create($request->toArray());

        return $newCust->cust_id;
    }

    //  Determine if the assigned parent has a partent of its own
    protected function validateParentID($parentID)
    {
        $parent = Customers::find($parentID);

        return $parent->parent_id ? $parent->parent_id : $parentID;
    }
}
