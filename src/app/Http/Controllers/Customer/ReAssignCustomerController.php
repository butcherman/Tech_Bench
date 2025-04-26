<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReAssignSiteRequest;
use App\Jobs\Customer\ReAssignSiteJob;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ReAssignCustomerController extends Controller
{
    /**
     * Show the form for moving a customer site to new customer ID.
     */
    public function edit(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/Admin/ReAssignSite');
    }

    /**
     * Move the customer site to the new Customer ID.
     */
    public function update(ReAssignSiteRequest $request): RedirectResponse
    {
        ReAssignSiteJob::dispatch(
            $request->get('moveSiteId'),
            $request->get('toCustomer')
        );

        Log::notice(
            'Move Customer Site called by ' . $request->user()->username,
            $request->toArray()
        );

        return back()->with('success', __('cust.admin.re-assigned'));
    }
}
