<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerDeletedItemsController extends Controller
{
    /**
     * Show a list of items that have been soft deleted for the customer.
     */
    public function __invoke(Customer $customer): Response
    {
        $this->authorize('manage', $customer);

        return Inertia::render('Customer/DeletedItems', [
            'customer' => fn() => $customer,
            'deleted-items' => [
                'equipment' => $customer->CustomerEquipment()->onlyTrashed()->get(),
                'contacts' => $customer->CustomerContact()->onlyTrashed()->get(),
                'notes' => $customer->CustomerNote()->onlyTrashed()->get(),
                'files' => $customer->CustomerFile()->onlyTrashed()->get(),
            ]
        ]);
    }
}
