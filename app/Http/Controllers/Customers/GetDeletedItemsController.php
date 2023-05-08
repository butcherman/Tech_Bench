<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;

class GetDeletedItemsController extends Controller
{
    /**
     * Return items that have been soft deleted for a specific customer
     */
    public function __invoke(Customer $customer)
    {
        $this->authorize('manage', $customer);

        return response()->json([
            'equipment' => CustomerEquipment::getTrashed($customer),
            'contacts' => CustomerContact::getTrashed($customer),
        ]);
    }
}
