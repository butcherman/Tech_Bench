<?php

namespace App\Http\Controllers\Customers;

use App\Events\Customers\Admin\CustomerIdChangedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerIdRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerIdController extends Controller
{
    /**
     * Show customer quick-search page for customers
     */
    public function index()
    {
        $this->authorize('manage', Customer::class);
        return Inertia::render('Customers/Id/Index');
    }

    /**
     * Form to allow changing a customers ID number
     */
    public function edit($id)
    {
        $this->authorize('manage', Customer::class);
        return Inertia::render('Customers/Id/Edit', [
            'details' => Customer::where('slug', $id)->firstOrFail(),
        ]);
    }

    /**
     * Submit the customers new ID number
     */
    public function update(CustomerIdRequest $request, $id)
    {
        $cust = Customer::findOrFail($id);
        $cust->update($request->only(['cust_id']));

        event(new CustomerIdChangedEvent($cust, $request->current_id));
        return redirect()->route('admin.index')->with([
            'message' => 'Customer ID Updated',
            'type'    => 'success',
        ]);
    }
}
