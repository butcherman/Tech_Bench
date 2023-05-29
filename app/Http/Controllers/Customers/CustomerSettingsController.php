<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerSettingsRequest;
use App\Models\Customer;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerSettingsController extends Controller
{
    use AppSettingsTrait;

    public function get()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Customers/Settings', [
            'select-id' => config('customer.select_id'),
            'update-slug' => config('customer.update_slug'),
            'default-state' => config('customer.default_state'),
        ]);
    }

    public function set(CustomerSettingsRequest $request)
    {
        foreach ($request->all() as $key => $value) {
            $this->saveSettings($request->getConfigKey($key), $value);
        }

        Log::notice('Customer Default Settings have been updated by '.$request->user()->username);

        return back()->with('success', __('admin.config_updated'));
    }
}
