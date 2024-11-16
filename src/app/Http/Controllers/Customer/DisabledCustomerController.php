<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Inertia\Inertia;
use Inertia\Response;

class DisabledCustomerController extends Controller
{
    /**
     * Show the list of customers that have been soft deleted
     */
    public function __invoke(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/Disabled', [
            'disabled-list' => Customer::onlyTrashed()
                ->get()
                ->makeHidden(['CustomerSite'])
                ->makeVisible(['deleted_at', 'deleted_reason']),
        ]);
    }
}
