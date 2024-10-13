<?php

namespace App\Service\Customer;

use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Requests\Customer\CustomerSiteRequest;
use App\Jobs\Customer\DestroyCustomerJob;
use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Support\Str;

class CustomerService
{
    /**
     * Create a new customer
     */
    public function createCustomer(CustomerRequest $requestData): Customer
    {
        $slug = $this->generateSlug($requestData->name);
        $requestData->merge(['slug' => $slug]);

        $newCustomer = Customer::create($requestData->only([
            'cust_id',
            'name',
            'dba_name',
            'slug',
        ]));

        $this->createSite($requestData, $newCustomer);

        return $newCustomer;
    }

    /**
     * Update existing customer
     */
    public function updateCustomer(
        CustomerRequest $requestData,
        Customer $customer
    ): Customer {
        // If name has changed, generate a new slug
        if ($requestData->name !== $customer->name) {
            $slug = $this->generateSlug($requestData->name);
            $customer->slug = $slug;
        }

        $customer->name = $requestData->name;
        $customer->dba_name = $requestData->dba_name;
        $customer->primary_site_id = $requestData->primary_site_id;

        $customer->save();

        return $customer;
    }

    public function destroyCustomer(
        Customer $customer,
        ?string $reason = null,
        ?bool $force = false
    ): void {
        if ($force) {
            dispatch(new DestroyCustomerJob($customer));

            return;
        }

        $customer->deleted_reason = $reason;
        $customer->save();
        $customer->delete();
    }

    public function restoreCustomer(Customer $customer): void
    {
        $customer->restore();
    }

    /**
     * Create a new customer site
     */
    public function createSite(
        CustomerRequest|CustomerSiteRequest $requestData,
        Customer $customer
    ): CustomerSite {
        $site = new CustomerSite($requestData->only([
            'address',
            'city',
            'state',
            'zip',
        ]));
        $site->site_name = $requestData->site_name ?: $requestData->name;
        $site->site_slug = $requestData->site_slug ?: $requestData->slug;

        $customer->CustomerSite()->save($site);

        return $site;
    }

    /**
     * Generate a unique slug to reference the customer in URL
     */
    protected function generateSlug(string $name, ?bool $isSite = false): string
    {
        $index = 0;
        $slug = Str::slug($name);

        $model = $isSite ? CustomerSite::class : Customer::class;
        $param = $isSite ? 'site_slug' : 'slug';

        // Verify that this slug is unique
        while ($model::where($param, $slug)->first()) {
            $index++;
            $slug = Str::slug($name).'-'.$index;
        }

        return $slug;
    }
}
