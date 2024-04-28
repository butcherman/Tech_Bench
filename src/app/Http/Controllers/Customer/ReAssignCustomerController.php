<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReAssignSiteRequest;
use App\Jobs\Customer\ReAssignSiteJob;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReAssignCustomerController extends Controller
{
    /**
     * Show the form for moving a customer site from one customer to another
     */
    public function edit()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/ReAssign');
    }

    /**
     * Trigger the job to move the customer site
     */
    public function update(ReAssignSiteRequest $request)
    {
        dispatch(new ReAssignSiteJob($request));

        return back()->with('success', 'Re-Assignment job started in background.  This may take some time');
    }
}
