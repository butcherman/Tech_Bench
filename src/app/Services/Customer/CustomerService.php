<?php

namespace App\Services\Customer;

use App\Events\Customer\CustomerSlugChangedEvent;
use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CustomerService
{
    /**
     * Create a new customer
     */
    public function createCustomer(Collection $requestData): Customer
    {
        $slug = $this->generateSlug(
            $requestData->get('name'),
            $requestData->get('city')
        );

        $newCustomer = new Customer($requestData->only([
            'cust_id',
            'name',
            'dba_name',
            'slug',
        ])->toArray());

        $newCustomer->slug = $slug;
        $newCustomer->save();

        $this->createSite($requestData, $newCustomer);

        return $newCustomer;
    }

    /**
     * Update existing customer
     */
    public function updateCustomer(Collection $requestData, Customer $customer): Customer
    {
        // If name has changed, generate a new slug
        if (config('customer.update_slug')) {
            if ($requestData->get('name') !== $customer->name) {
                $oldSlug = $customer->slug;

                $slug = $this->generateSlug(
                    $requestData->get('name'),
                    $customer->CustomerSite[0]->city
                );
                $customer->slug = $slug;

                event(new CustomerSlugChangedEvent($customer, $oldSlug, $slug));
            }
        }

        $customer->name = $requestData->get('name');
        $customer->dba_name = $requestData->get('dba_name');
        $customer->primary_site_id = $requestData->get('primary_site_id');

        $customer->save();

        return $customer;
    }

    /**
     * Soft Delete or Force Delete a customer
     */
    public function destroyCustomer(
        Customer $customer,
        ?string $reason = null,
        ?bool $force = false
    ): void {
        if ($force) {
            $customer->forceDelete();

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
        Collection $requestData,
        ?Customer $customer = null
    ): CustomerSite {
        // If the customer was not passed with request, find from customer id
        if (is_null($customer)) {
            $customer = Customer::find($requestData->get('cust_id'));
        }

        $site = new CustomerSite($requestData->only([
            'address',
            'city',
            'state',
            'zip',
        ])->toArray());

        $slug = $this->generateSlug(
            $requestData->get('site_name') ?: $requestData->get('name'),
            $requestData->get('city'),
            true
        );
        $site->site_name = $requestData->get('site_name')
            ?: $requestData->get('name');
        $site->site_slug = $slug;

        $customer->CustomerSite()->save($site);

        return $site;
    }

    /**
     * Update an existing customer site
     */
    public function updateSite(Collection $requestData, CustomerSite $site): CustomerSite
    {
        // If name has changed, generate a new slug
        if (config('customer.update_slug')) {
            if ($requestData->get('site_name') !== $site->site_name) {
                $slug = $this->generateSlug(
                    $requestData->get('site_name'),
                    $requestData->get('city'),
                    true
                );
                $requestData->merge(['site-slug' => $slug]);
            }
        }

        $site->update($requestData->except(['cust_name'])->toArray());

        return $site;
    }

    /**
     * Destroy a customer site
     */
    public function destroySite(
        CustomerSite $site,
        string $reason,
        ?bool $force = false
    ): void {
        if ($force) {
            CustomerSite::withoutBroadcasting(function () use ($site) {
                $site->forceDelete();
            });

            return;
        }

        $site->deleted_reason = $reason;
        $site->save();
        $site->delete();
    }

    /**
     * Destroy All Customer Sites
     */
    public function destroyAllSites(Customer $customer): void
    {
        CustomerSite::withoutBroadcasting(function () use ($customer) {
            // Remove the Primary Site ID from the customer
            $customer->primary_site_id = null;
            $customer->save();

            $siteList = $customer->CustomerSite;
            foreach ($siteList as $site) {
                $this->destroySite($site, 'Force Deleting Customer', true);
            }
        });
    }

    /**
     * Generate a unique slug to reference the customer in URL
     */
    protected function generateSlug(
        string $name,
        string $city,
        ?bool $isSite = false
    ): string {
        $index = 0;
        $slug = Str::slug($name);

        $model = $isSite ? CustomerSite::class : Customer::class;
        $param = $isSite ? 'site_slug' : 'slug';

        while ($model::where($param, $slug)->first()) {
            $slug = Str::slug($name).'-'.Str::slug($city);
            if ($index > 0) {
                $slug .= '-'.$index;
            }
            $index++;
        }

        return $slug;
    }
}
