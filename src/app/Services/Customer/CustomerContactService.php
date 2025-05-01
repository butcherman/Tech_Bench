<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Traits\PhoneNumberTrait;
use Illuminate\Support\Collection;

class CustomerContactService
{
    use PhoneNumberTrait;

    /**
     * Create a new Customer Contact
     */
    public function createCustomerContact(Collection $requestData, Customer $customer): CustomerContact
    {
        $contact = new CustomerContact(
            $requestData->except(['phones', 'site_list'])->toArray()
        );
        $customer->CustomerContact()->save($contact);

        $contact->Sites()->sync($requestData->get('site_list'));

        $this->syncContactPhones($contact, $requestData->get('phones'));

        return $contact;
    }

    /**
     * Update an existing Customer Contact
     */
    public function updateCustomerContact(Collection $requestData, CustomerContact $contact): CustomerContact
    {
        $contact->update($requestData->except(['phones', 'site_list'])->toArray());

        $contact->Sites()->sync($requestData->get('site_list'));

        $this->syncContactPhones($contact, $requestData->get('phones'));

        return $contact->refresh();
    }

    /**
     * Delete a Customer Contact
     */
    public function destroyContact(CustomerContact $contact, bool $force = false): void
    {
        if ($force) {
            $contact->forceDelete();

            return;
        }

        $contact->delete();
    }

    /**
     * Add/Remove any phone numbers for the selected contact
     */
    protected function syncContactPhones(CustomerContact $contact, array $phoneList): void
    {
        $currentList = $contact->CustomerContactPhone()->pluck('id')->toArray();
        $validList = [];

        foreach ($phoneList as $phoneNumber) {
            // If the number field is blank, do not process
            if (empty($phoneNumber['number'])) {
                continue;
            }

            $type = $this->getPhoneNumberType($phoneNumber['type']);
            $saveData = [
                'cont_id' => $contact->cont_id,
                'phone_number' => $this->cleanPhoneString($phoneNumber['number']),
                'extension' => $phoneNumber['ext'],
                'phone_type_id' => $type->phone_type_id,
            ];

            // Check if this is a new or existing number
            if (array_key_exists('id', $phoneNumber)) {
                $phone = CustomerContactPhone::find($phoneNumber['id']);
                $phone->update($saveData);
            } else {
                $phone = CustomerContactPhone::create($saveData);
            }

            $validList[] = $phone->id;
        }

        // $contact->CustomerContactPhone()->sync($validList);
        $removed = array_diff($currentList, $validList);
        CustomerContactPhone::destroy($removed);
    }
}
