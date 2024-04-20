<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSettingsRequest;
use App\Models\Customer;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerAdminController extends Controller
{
    use AppSettingsTrait;

    /**
     * Show the form for editing customer settings
     */
    public function edit()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/Admin', [
            'select_id' => (bool) config('customer.select_id'),
            'update_slug' => (bool) config('customer.update_slug'),
            'default_state' => config('customer.default_state'),
            'auto_purge' => (bool) config('customer.auto_purge'),
        ]);
    }

    /**
     * Update the customer settings
     */
    public function update(CustomerSettingsRequest $request)
    {
        $this->saveSettingsArray($request->toArray(), 'customer');

        Log::stack(['daily', 'cust'])->info('Customer Settings Updated By '.
            $request->user()->username, $request->toArray());

        return back()->with('success', 'Customer Settings Updated');
    }
}
