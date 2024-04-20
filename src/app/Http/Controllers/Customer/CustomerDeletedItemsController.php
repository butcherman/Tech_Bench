<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerDeletedItemsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Customer $customer)
    {
        $this->authorize('manage', $customer);

        return Inertia::render('Customer/DeletedItems', [
            'customer' => $customer,
            'deleted-items' => [
                'equipment' => $customer->CustomerEquipment()->onlyTrashed()->get(),
                'contacts' => $customer->CustomerContact()->onlyTrashed()->get(),
                'notes' => $customer->CustomerNote()->onlyTrashed()->get(),
                'files' => $customer->CustomerFile()->onlyTrashed()->get(),
            ],
        ]);
    }
}
