<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAlert;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerAlertController extends Controller
{
    /**
     * Show a list of Customer Alerts for the selected Customer.
     */
    public function index(Customer $customer): Response
    {
        $this->authorize('viewAny', CustomerAlert::class);

        return Inertia::render('Customer/Alert/Index', [
            'customer' => fn() => $customer,
            'alerts' => fn() => $customer->CustomerAlert,
        ]);
    }

    /**
     *
     */
    public function store(Request $request)
    {
        //
        return 'store';
    }

    /**
     *
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     *
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
