<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Support\Collection;

class CustomerContactService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected CustomerContactPhoneService $phoneSvc) {}

    /**
     * Create a new contact for a customer
     */
    public function createCustomerContact(Collection $requestData, Customer $customer): CustomerContact
    {
        $contact = new CustomerContact($requestData->except(['phones', 'site_list'])->toArray());
        $customer->CustomerContact()->save($contact);

        $this->processContact($requestData, $contact);

        return $contact;
    }

    /**
     * Update an existing Customer Contact
     */
    public function updateCustomerContact(Collection $requestData, CustomerContact $contact): CustomerContact
    {
        $contact->update($requestData->except(['phones', 'site_list'])->toArray());

        $this->processContact($requestData, $contact);

        return $contact;
    }

    /**
     * Update Customer Sites and phone numbers
     */
    public function processContact(Collection $requestData, CustomerContact $contact): void
    {
        // Attach sites
        $contact->CustomerSite()->sync($requestData->site_list);

        // Process Phone Numbers
        $this->phoneSvc
            ->processCustomerContactPhones($contact, $requestData->phones);
    }

    /**
     * Delete a customer contact
     */
    public function destroyContact(CustomerContact $contact, ?bool $force = false): void
    {
        if ($force) {
            $contact->forceDelete();

            return;
        }

        $contact->delete();
    }

    /**
     * Restore a soft deleted contact
     */
    public function restoreContact(CustomerContact $contact): void
    {
        $contact->restore();
    }
}
