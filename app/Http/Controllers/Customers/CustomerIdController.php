<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerIdRequest;
use App\Models\Customer;

class CustomerIdController extends Controller
{
    /**
     * Show search box for finding a customer
     */
    public function index()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customers/Id/Index');
    }

    /**
     * Show the form for changing a customer ID
     */
    public function show(Customer $change_id)
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customers/Id/Edit', [
            'customer' => $change_id,
        ]);
    }

    /**
     * Update the Customer ID for a specific customer
     */
    public function update(CustomerIdRequest $request, Customer $change_id)
    {
        $change_id->update($request->only(['cust_id']));

        Log::stack(['daily', 'cust'])->notice('Customer ID for '.$change_id->name.' has been updated to '.$change_id->cust_id.' by '.$request->user()->username);
        return redirect(route('customers.show', $change_id->slug))->with('success', __('cust.admin.change_id'));
    }
}
