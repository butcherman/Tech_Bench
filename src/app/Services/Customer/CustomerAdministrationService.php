<?php

namespace App\Services\Customer;

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
    // public function createCustomerAlert(
    //     CustomerAlertRequest $requestData,
    //     Customer $customer
    // ): void {
    //     $newAlert = new CustomerAlert($requestData->all());

    //     $customer->CustomerAlert()->save($newAlert);

    //     Log::info('New Customer Alert created for '.$customer->name.' by '.
    //         $requestData->user()->username, $newAlert->toArray());

    //     event(new CustomerAlertEvent($customer));
    // }

    /**
     * Update an existing Customer Alert
     */
    // public function updateCustomerAlert(
    //     CustomerAlertRequest $requestData,
    //     Customer $customer,
    //     CustomerAlert $alert
    // ): void {
    //     $alert->update($requestData->all());

    //     Log::info('Customer Alert Updated for '.$customer->name.
    //     ' by '.$requestData->user()->username, $alert->toArray());

    //     event(new CustomerAlertEvent($customer));
    // }

    /**
     * Remove an existing Customer Alert
     */
    // public function removeCustomerAlert(Customer $customer, CustomerAlert $alert)
    // {
    //     $alert->delete();

    //     Log::info('Customer Alert for '.$customer->name.
    //         ' deleted by '.request()->user()->username, $alert->toArray());

    //     event(new CustomerAlertEvent($customer));
    // }
}
