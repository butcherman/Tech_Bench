<?php

namespace App\Services\Customer;

use App\Events\Customer\CustomerSlugChangedEvent;
use App\Facades\DbException;
use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CustomerService
{
    /**
     * Create a new customer and the customers primary site.
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
        // Update customer primary information
        $customer->name = $requestData->get('name');
        $customer->dba_name = $requestData->get('dba_name');
        $customer->primary_site_id = $requestData->get('primary_site_id');

        // If name has changed, generate a new slug if allowed
        if ($customer->isDirty('name')) {
            if (config('customer.update_slug')) {
                $slug = $this->generateSlug(
                    $requestData->get('name'),
                    $customer->Sites[0]->city
                );
                $customer->slug = $slug;

                CustomerSlugChangedEvent::broadcast(
                    $customer,
                    $customer->getOriginal('slug')
                )
                    ->toOthers();
            }
        }

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
            try {
                $customer->forceDelete();
            } catch (QueryException $e) {
                DbException::check($e);
            }

            return;
        }

        $customer->deleted_reason = $reason;
        $customer->save();
        $customer->delete();
    }

    /**
     * Restore a customer that was soft deleted.
     */
    public function restoreCustomer(Customer $customer): void
    {
        $customer->restore();
    }

    /**
     * Create a new customer site
     */
    public function createSite(Collection $requestData, ?Customer $customer = null): CustomerSite
    {
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

        $customer->Sites()->save($site);

        return $site;
    }

    /**
     * Update an existing customer site
     */
    public function updateSite(Collection $requestData, CustomerSite $site): CustomerSite
    {
        // If name has changed, generate a new slug if allowed
        $site->site_name = $requestData->get('site_name');
        $site->address = $requestData->get('address');
        $site->city = $requestData->get('city');
        $site->state = $requestData->get('state');
        $site->zip = $requestData->get('zip');

        if ($site->isDirty('site_name')) {
            if (config('customer.update_slug')) {
                $slug = $this->generateSlug(
                    $requestData->get('site_name'),
                    $requestData->get('city'),
                    true
                );

                $site->site_slug = $slug;
            }
        }

        $site->save();

        return $site->refresh();
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
            Log::notice('Force Deleting Customer site '.$site->site_name, [
                'reason' => $reason,
                'customer_id' => $site->cust_id,
                'site_id' => $site->cust_site_id,
            ]);

            CustomerSite::withoutBroadcasting(function () use ($site) {
                $site->forceDelete();
            });

            return;
        }

        Log::notice('Deleting Customer site '.$site->site_name, [
            'reason' => $reason,
            'customer_id' => $site->cust_id,
            'site_id' => $site->cust_site_id,
        ]);

        $site->deleted_reason = $reason;
        $site->save();
        $site->delete();
    }

    /**
     * Restore a Customer Site.
     */
    public function restoreSite(CustomerSite $site): void
    {
        $site->restore();
    }

    /**
     * Generate a unique slug to reference the customer or site in URL
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
            $slug = Str::slug($name.'-'.$city);
            if ($index > 0) {
                $slug .= '-'.$index;
            }
            $index++;
        }

        return $slug;
    }
}
