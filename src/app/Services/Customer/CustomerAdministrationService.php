<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerAlert;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;

class CustomerAdministrationService
{
    use AppSettingsTrait;

    /**
     * Update Customer Administration Settings
     */
    public function updateCustomerSettings(Collection $requestData): void
    {
        $this->saveSettingsArray($requestData->toArray(), 'customer');
    }

    /**
     * Create a new Customer Alert
     */
    public function createCustomerAlert(
        Collection $requestData,
        Customer $customer
    ): void {
        $newAlert = new CustomerAlert($requestData->all());

        $customer->CustomerAlert()->save($newAlert);
    }

    /**
     * Update an existing Customer Alert
     */
    public function updateCustomerAlert(
        Collection $requestData,
        CustomerAlert $alert
    ): void {
        $alert->update($requestData->all());
    }

    /**
     * Remove an existing Customer Alert
     */
    public function destroyCustomerAlert(CustomerAlert $alert): void
    {
        $alert->delete();
    }
}
