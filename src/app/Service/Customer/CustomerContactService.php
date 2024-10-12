<?php

namespace App\Service\Customer;

use App\Enum\CrudAction;
use App\Events\Customer\CustomerContactEvent;
use App\Http\Requests\Customer\CustomerContactRequest;
use App\Models\Customer;
use App\Models\CustomerContact;

class CustomerContactService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected CustomerContactPhoneService $phoneSvc) {}

    /**
     * Create a new contact for a customer
     */
    public function createCustomerContact(
        CustomerContactRequest $requestData,
        Customer $customer
    ): CustomerContact {
        $contact = new CustomerContact($requestData->except(['phones', 'site_list']));
        $customer->CustomerContact()->save($contact);

        $this->processContact($requestData, $contact);

        event(new CustomerContactEvent($contact, CrudAction::Create));

        return $contact;
    }

    /**
     * Update an existing Customer Contact
     */
    public function updateCustomerContact(
        CustomerContactRequest $requestData,
        CustomerContact $contact
    ): CustomerContact {
        $contact->update($requestData->except(['phones', 'site_list']));

        $this->processContact($requestData, $contact);

        event(new CustomerContactEvent($contact, CrudAction::Update));

        return $contact;
    }

    /**
     * Update Customer Sites and phone numbers
     */
    public function processContact(
        CustomerContactRequest $requestData,
        CustomerContact $contact
    ): void {
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

        event(new CustomerContactEvent($contact, CrudAction::Destroy));
    }

    /**
     * Restore a soft deleted contact
     */
    public function restoreContact(CustomerContact $contact): void
    {
        $contact->restore();

        event(new CustomerContactEvent($contact, CrudAction::Restore));
    }
}
