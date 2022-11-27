<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Customer;

class DeactivatedCustomerController extends Controller
{
    /**
     * Show a listing of all soft deleted customers
     */
    public function __invoke()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customers/Deactivated', [
            'customers' => Customer::onlyTrashed()->get()->makeVisible('deleted_at'),
        ]);
    }
}
