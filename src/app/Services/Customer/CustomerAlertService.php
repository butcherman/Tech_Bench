<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerAlert;
use Illuminate\Support\Collection;

class CustomerAlertService
{
    /**
     * Create a new Customer Alert
     */
    public function createCustomerAlert(Collection $requestData, Customer $customer): void
    {
        $newAlert = new CustomerAlert($requestData->all());

        $customer->CustomerAlert()->save($newAlert);
    }

    /**
     * Update an existing Customer Alert
     */
    public function updateCustomerAlert(Collection $requestData, CustomerAlert $alert): void
    {
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
