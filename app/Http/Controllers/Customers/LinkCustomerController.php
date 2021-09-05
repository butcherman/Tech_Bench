<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use App\Events\CustomerLinkedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\LinkCustomerRequest;

class LinkCustomerController extends Controller
{
    /**
     * Link or Unlink the customer to a parent customer
     */
    public function __invoke(LinkCustomerRequest $request)
    {
        if($request->add)
        {
            $msg = $this->addParent($request->cust_id, $request->parent_id);
        }
        else
        {
            $msg = $this->removeParent($request->cust_id);
        }

        return back()->with($msg);
    }

    /**
     * Add a link for multi-site customer
     */
    protected function addParent($cust, $parent)
    {
        //  Determine if the selected Parent ID has a parent ID of its own
        $p = Customer::findOrFail($parent);
        if($p->parent_id)
        {
            $parent = $p->parent_id;
        }

        Customer::find($cust)->update([
            'parent_id' => $parent,
        ]);

        event(new CustomerLinkedEvent($cust, true));
        return [
            'message' => 'Customer successfully linked',
            'type'    => 'success',
        ];
    }

    /**
     * Remove a multi-site link
     */
    protected function removeParent($cust)
    {
        Customer::findOrFail($cust)->update(['parent_id' => null]);

        event(new CustomerLinkedEvent($cust, false));
        return [
            'message' => 'Customer link removed',
            'type'    => 'warning',
        ];
    }
}
