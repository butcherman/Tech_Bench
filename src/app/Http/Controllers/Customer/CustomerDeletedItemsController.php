<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
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

        return Inertia::render('Customer/Admin/DeletedItems', [
            'customer' => fn () => $customer,
            'deleted-items' => [
                'equipment' => $customer->Equipment()->onlyTrashed()->get(),
                'contacts' => $customer->Contacts()->onlyTrashed()->get(),
                'notes' => $customer->Notes()->onlyTrashed()->get(),
                'files' => $customer->Files()->onlyTrashed()->get(),
                'sites' => $customer->Sites()
                    ->onlyTrashed()
                    ->get()
                    ->makeVisible(['deleted_at', 'deleted_reason']),
            ],
        ]);
    }
}
