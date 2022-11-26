<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Support\Facades\Log;
use App\Traits\AppSettingsTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerSettingsRequest;

class SetCustomerSettingsController extends Controller
{
    use AppSettingsTrait;

    /**
     * Update the default customer settings
     */
    public function __invoke(CustomerSettingsRequest $request)
    {
        foreach($request->all() as $key => $value)
        {
            $this->saveSettings($request->getConfigKey($key), $value);
        }

        Log::notice('Customer Default Settings have been updated by '.$request->user()->username);
        return back()->with('success', __('admin.config_updated'));
    }
}
