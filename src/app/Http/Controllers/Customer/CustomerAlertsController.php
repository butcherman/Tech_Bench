<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerAlertRequest;
use App\Models\Customer;
use App\Models\CustomerAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerAlertsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Customer $customer)
    {
        $this->authorize('viewAny', CustomerAlert::class);

        return Inertia::render('Customer/Alert/Index', [
            'customer' => $customer,
            'alerts' => $customer->CustomerAlert,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerAlertRequest $request, Customer $customer)
    {
        $newAlert = CustomerAlert::create([
            'cust_id' => $customer->cust_id,
            'message' => $request->message,
            'type' => $request->type,
        ]);

        Log::channel('cust')->info(
            'New Customer Alert created for '.
            $customer->name.' by '.$request->user()->username,
            $newAlert->toArray()
        );

        return back()->with('success', __('cust.alert.created'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerAlertRequest $request, Customer $customer, CustomerAlert $alert)
    {
        $alert->update([
            'message' => $request->message,
            'type' => $request->type,
        ]);

        Log::channel('cust')->info('Customer Alert Updated for '.$customer->name.
            ' by '.$request->user()->username, $alert->toArray());

        return back()->with('success', __('cust.alert.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Customer $customer, CustomerAlert $alert)
    {
        $this->authorize('delete', $alert);

        $alert->delete();

        Log::channel('cust')->info('Customer Alert for '.$customer->name.
            ' deleted by '.$request->user()->username, $alert->toArray());

        return back()->with('warning', __('cust.alert.destroy'));
    }
}