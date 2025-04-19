<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Traits\AppSettingsTrait;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CustomerAdministrationService extends CustomerService
{
    use AppSettingsTrait;

    /*
    |---------------------------------------------------------------------------
    | Customer Configuration Settings
    |---------------------------------------------------------------------------
    */

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

    /*
    |---------------------------------------------------------------------------
    | Basic Customer Administration
    |---------------------------------------------------------------------------
    */

    /**
     * Get a list of disabled customers - ignore customers that have a current
     * running job pending (retrieved from cache).
     */
    public function getDisabledCustomers(): EloquentCollection
    {
        $disabledList = Customer::onlyTrashed()
            ->get()
            ->makeHidden(['CustomerSite'])
            ->makeVisible(['deleted_at', 'deleted_reason']);

        $workingJobs = Cache::get('queued-customers', []);

        return $disabledList->reject(function ($value) use ($workingJobs) {
            return in_array($value->cust_id, $workingJobs);
        })->values();
    }

    /**
     * Add a customer ID to the list of working jobs.
     */
    public function addToWorkingJobs(Customer $customer): void
    {
        $workingJobs = Cache::get('queued-customers', []);
        $workingJobs[] = $customer->cust_id;

        Cache::put('queued-customers', $workingJobs, now()->addDays(2));
    }

    /**
     * Force delete all associated customer sites
     */
    public function destroyCustomerSites(Customer $customer): void
    {
        // Remove Primary Site ID
        $customer->primary_site_id = null;
        $customer->save();

        foreach ($customer->CustomerSiteList as $site) {
            $this->destroySite($site, 'Force Deleting Customer', true);
        }
    }




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
