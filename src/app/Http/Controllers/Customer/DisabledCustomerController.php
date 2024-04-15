<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DisabledCustomerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
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
