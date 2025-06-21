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
     * Show the form for editing the resource.
     */
    public function edit(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render(
            'Customer/Admin/Administration',
            $this->svc->getCustomerSettings()
        );
    }

    /**
     * Update the resource in storage.
     */
    public function update(CustomerSettingsRequest $request): RedirectResponse
    {
        $this->svc->updateCustomerSettings($request->safe()->collect());

        Log::info(
            'Customer Settings updated by '.$request->user()->username,
            $request->toArray()
        );

        return back()->with('success', __('cust.admin.settings_updated'));
    }
}
