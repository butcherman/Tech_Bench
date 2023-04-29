<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Inertia\Inertia;

class GetCustomerSettingsController extends Controller
{
    /**
     * Customer Generic Settings page
     */
    public function __invoke()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customers/Settings', [
            'select-id' => config('customer.select_id'),
            'update-slug' => config('customer.update_slug'),
            'default-state' => config('customer.default_state'),
        ]);
    }
}
