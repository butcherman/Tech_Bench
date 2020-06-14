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

    public function updateCustomer($custID, $details)
    {
        Customers::find($custID)->update($details->toArray());
        return true;
    }

    public function deactivateCustomer($custID)
    {
        Customers::find($custID)->delete();
        return true;
    }

    public function linkParent($request)
    {
        if(isset($request->parent_id))
        {
            return $this->attachParent($request->cust_id, $request->parent_id);
        }

        return $this->detachParent($request->cust_id);
    }

    //  Determine if the assigned parent has a partent of its own
    protected function validateParentID($parentID)
    {
        $parent = Customers::find($parentID);

        return $parent->parent_id ? $parent->parent_id : $parentID;
    }

    //  Link the parent to the customer
    protected function attachParent($custID, $parentID)
    {
        $parent = $this->validateParentID($parentID);
        Customers::find($custID)->update(['parent_id' => $parent]);

        return true;
    }

    //  Remove the parent from the customer
    protected function detachParent($custID)
    {
        Customers::find($custID)->update(['parent_id' => null]);

        return true;
    }
}
