<?php

namespace App\Service\Customer;

use App\Http\Requests\Customer\CustomerSettingsRequest;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;

class CustomerAdministrationService
{
    use AppSettingsTrait;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Update Customer Administration Settings
     */
    public function updateCustomerSettings(CustomerSettingsRequest $requestData)
    {
        $this->saveSettingsArray($requestData->toArray(), 'customer');

        Log::info('Customer Settings Updated By '.
            $requestData->user()->username, $requestData->toArray());
    }
}
