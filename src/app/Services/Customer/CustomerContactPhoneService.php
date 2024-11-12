<?php

namespace App\Services\Customer;

use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Services\Misc\PhoneNumberService;

class CustomerContactPhoneService extends PhoneNumberService
{
    /**
     * Cycle through a Customer Contacts phone numbers and modify them in db.
     */
    public function processCustomerContactPhones(
        array $phoneNumbers,
        CustomerContact $contact
    ): void {
        // Numbers that are added/updated
        $phoneIdList = [];

        // Numbers that are already in the database
        $existingNumbers = $contact->CustomerContactPhone()
            ->pluck('id')
            ->toArray();

        foreach ($phoneNumbers as $number) {
            $phoneIdList[] = $this->processPhoneNumber($contact, $number);
        }

        // Remove any old numbers....
        $removedNumbers = array_diff($existingNumbers, $phoneIdList);
        CustomerContactPhone::destroy($removedNumbers);
    }

    /**
     * Add or Update existing records
     */
    protected function processPhoneNumber(
        CustomerContact $contact,
        array $number
    ): int {
        if (is_null($number['number'])) {
            return 0;
        }

        // Determine if we are creating a record or updating it
        if (isset($number['id'])) {
            return $this->updatePhoneNumber($number);
        }

        $newNumber = $contact->CustomerContactPhone()->create([
            'phone_type_id' => $this->getPhoneNumberType($number['type'])
                ->phone_type_id,
            'phone_number' => $this->cleanPhoneString($number['number']),
            'extension' => $number['ext'],
        ]);

        return $newNumber->id;
    }

    /**
     * Update an existing phone number, if it has changed
     */
    protected function updatePhoneNumber(array $phone): int
    {
        $currentRecord = CustomerContactPhone::find($phone['id']);
        $currentRecord->update([
            'phone_type_id' => $this->getPhoneNumberType($phone['type'])
                ->phone_type_id,
            'phone_number' => $this->cleanPhoneString($phone['number']),
            'extension' => $phone['ext'],
        ]);

        return $currentRecord->id;
    }
}
