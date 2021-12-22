<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Http\Controllers\Controller;

class DeactivatedCustomerController extends Controller
{
    /**
     * Handle the incoming request
     */
    public function __invoke(Request $request)
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customers/Deactivated', [
            'list' => Customer::onlyTrashed()->get()->makeVisible('deleted_at'),
        ]);
    }
}
