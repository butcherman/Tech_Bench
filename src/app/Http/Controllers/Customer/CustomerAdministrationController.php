<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerSettingsRequest;
use App\Models\Customer;
use App\Services\Customer\CustomerAdministrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerAdministrationController extends Controller
{
    public function __construct(protected CustomerAdministrationService $svc) {}

    /**
     * Show the form for editing Customer Settings.
     */
    public function edit(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customer/Admin', [
            'select_id' => fn () => (bool) config('customer.select_id'),
            'update_slug' => fn () => (bool) config('customer.update_slug'),
            'default_state' => fn () => config('customer.default_state'),
            'auto_purge' => fn () => (bool) config('customer.auto_purge'),
        ]);
    }

    /**
     * Update the Customer Settings.
     */
    public function update(CustomerSettingsRequest $request): RedirectResponse
    {
        $this->svc->updateCustomerSettings($request->collect());

        Log::info(
            'Customer Settings Updated By '.$request->user()->username,
            $request->toArray()
        );

        return back()->with('success', __('cust.admin.settings_updated'));
    }
}
