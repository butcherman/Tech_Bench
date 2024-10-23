<?php

namespace App\Service\Customer;

use App\Events\Customer\CustomerSlugChangedEvent;
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
        $newCustomer = new Customer($requestData->only([
            'cust_id',
            'name',
            'dba_name',
        ]));
        $newCustomer->slug = $this->generateSlug($requestData->name);
        $newCustomer->save();

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
            $oldSlug = $customer->slug;

            $slug = $this->generateSlug($requestData->name);
            $customer->slug = $slug;

            event(new CustomerSlugChangedEvent($customer, $oldSlug, $slug));
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
        ?Customer $customer
    ): CustomerSite {
        // If the customer was not passed with request, find from customer id
        if (is_null($customer)) {
            $customer = Customer::find($requestData->cust_id);
        }

        $site = new CustomerSite($requestData->only([
            'address',
            'city',
            'state',
            'zip',
        ]));

        $slug = $this->generateSlug($requestData->site_name ?: $requestData->name, true);
        $site->site_name = $requestData->site_name ?: $requestData->name;
        $site->site_slug = $slug;

        $customer->CustomerSite()->save($site);

        return $site;
    }

    /**
     * Update an existing customer site
     */
    public function updateSite(CustomerSiteRequest $requestData, CustomerSite $site): CustomerSite
    {
        // If name has changed, generate a new slug
        if ($requestData->site_name !== $site->site_name) {
            $slug = $this->generateSlug($requestData->site_name);
            $requestData->merge(['site-slug' => $slug]);
        }

        $site->update($requestData->except(['cust_name']));

        return $site;
    }

    /**
     * Destroy a customer site
     */
    public function destroySite(CustomerSite $site, string $reason, ?bool $force = false): void
    {
        if ($force) {
            $site->forceDelete();

            return;
        }

        $site->deleted_reason = $reason;
        $site->save();
        $site->delete();
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
