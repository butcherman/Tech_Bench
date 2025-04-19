<?php

namespace App\Services\Customer;

use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;

class CustomerAdministrationService
{
    use AppSettingsTrait;

    /**
     * Get the Customer Administration Settings data
     */
    public function getCustomerSettings(): Collection
    {
        return collect([
            'select-id' => fn() => config('customer.select_id'),
            'update-slug' => fn() =>  config('customer.update_slug'),
            'default-state' => fn() => config('customer.default_state'),
            'auto-purge' => fn() =>  config('customer.auto_purge'),
        ]);
    }

    /**
     * Update Customer Administration Settings data
     */
    public function updateCustomerSettings(Collection $requestData): void
    {
        $this->saveSettingsArray($requestData->toArray(), 'customer');
    }

    /**
     * Create a new Customer Alert
     */
    // public function createCustomerAlert(
    //     Collection $requestData,
    //     Customer $customer
    // ): void {
    //     $newAlert = new CustomerAlert($requestData->all());

    //     $customer->CustomerAlert()->save($newAlert);
    // }

    /**
     * Update an existing Customer Alert
     */
    // public function updateCustomerAlert(
    //     Collection $requestData,
    //     CustomerAlert $alert
    // ): void {
    //     $alert->update($requestData->all());
    // }

    /**
     * Remove an existing Customer Alert
     */
    // public function destroyCustomerAlert(CustomerAlert $alert): void
    // {
    //     $alert->delete();
    // }

    /**
     * Verify that a customer has at least one child site
     */
    // public function verifyCustomerChildren(?bool $fix = false)
    // {
    //     $failed = [];
    //     $customerList = Customer::withTrashed()->get();

    //     foreach ($customerList as $customer) {
    //         if ($customer->site_count === 0) {
    //             Log::debug('Customer '.$customer->name.' has no sites attached');
    //             $failed[] = $customer->only(['cust_id', 'name']);

    //             if ($fix) {
    //                 Log::notice('Deleting Customer without any attached sites', $customer->toArray());
    //                 $customer->forceDelete();
    //             }
    //         }
    //     }

    //     return $failed;
    // }
}
