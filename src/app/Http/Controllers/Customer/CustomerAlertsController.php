<?php

// TODO - Refactor

namespace App\Http\Controllers\Customer;

use App\Enum\CrudAction;
use App\Events\Customer\CustomerAlertEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerAlertRequest;
use App\Models\Customer;
use App\Models\CustomerAlert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerAlertsController extends Controller
{
    /**
     * Display a listing of alerts for the requested customer
     */
    public function index(Customer $customer): Response
    {
        $this->authorize('viewAny', CustomerAlert::class);

        return Inertia::render('Customer/Alert/Index', [
            'customer' => $customer,
            'alerts' => $customer->CustomerAlert,
        ]);
    }

    /**
     * Store a newly created customer alert
     */
    public function store(CustomerAlertRequest $request, Customer $customer): RedirectResponse
    {
        $newAlert = CustomerAlert::create([
            'cust_id' => $customer->cust_id,
            'message' => $request->message,
            'type' => $request->type,
        ]);

        Log::info(
            'New Customer Alert created for '.
            $customer->name.' by '.$request->user()->username,
            $newAlert->toArray()
        );

        event(new CustomerAlertEvent($customer, $newAlert, CrudAction::Create));

        return back()->with('success', __('cust.alert.created'));
    }

    /**
     * Update the specified customer alert
     */
    public function update(
        CustomerAlertRequest $request,
        Customer $customer,
        CustomerAlert $alert
    ): RedirectResponse {
        $alert->update([
            'message' => $request->message,
            'type' => $request->type,
        ]);

        Log::info('Customer Alert Updated for '.$customer->name.
            ' by '.$request->user()->username, $alert->toArray());

        event(new CustomerAlertEvent($customer, $alert, CrudAction::Update));

        return back()->with('success', __('cust.alert.updated'));
    }

    /**
     * Remove the specified customer alert
     */
    public function destroy(
        Request $request,
        Customer $customer,
        CustomerAlert $alert
    ): RedirectResponse {
        $this->authorize('delete', $alert);

        $alert->delete();

        Log::info('Customer Alert for '.$customer->name.
            ' deleted by '.$request->user()->username, $alert->toArray());

        event(new CustomerAlertEvent($customer, $alert, CrudAction::Destroy));

        return back()->with('warning', __('cust.alert.destroy'));
    }
}