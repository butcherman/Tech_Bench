<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeactivatedCustomersController extends Controller
{
    /**
     *  Show all customers who have been deactivated
     */
    public function __invoke()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/listDeactivated', [
            'list' => Customer::onlyTrashed()->get()->makeVisible('deleted_at'),
        ]);
    }
}
