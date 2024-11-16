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
     * Show the form for moving a customer site from one customer to another
     */
    public function edit(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/ReAssign');
    }

    /**
     * Trigger the job to move the customer site
     */
    public function update(ReAssignSiteRequest $request): RedirectResponse
    {
        dispatch(new ReAssignSiteJob(
            $request->get('moveSiteId'),
            $request->get('toCustomer')
        ));

        Log::notice(
            'Move Customer Site Called by '.$request->user()->username,
            $request->toArray()
        );

        return back()
            ->with(
                'success',
                'Re-Assignment job started in background.  This may take some time'
            );
    }
}
