<?php

namespace App\Service\Customer;

use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CustomerAdministrationService
{
    use AppSettingsTrait;

    /**
     * Update Customer Administration Settings
     */
    public function updateCustomerSettings(Collection $requestData)
    {
        $this->saveSettingsArray($requestData->toArray(), 'customer');

        Log::info(
            'Customer Settings Updated By '.request()->user()->username,
            $requestData->toArray()
        );
    }
}
