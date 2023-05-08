<?php

namespace App\Actions;

use App\Models\CustomerContact;
use JeroenDesloovere\VCard\VCard;

class BuildContactVCard
{
    public function buildCustomerContact(CustomerContact $contact)
    {
        $vCard = new VCard;
        $name = explode(' ', $contact->name);

        //  Basic information
        $vCard->addName(isset($name[1]) ? $name[1] : null, $name[0]);
        $vCard->addCompany($contact->customer->name);
        $vCard->addAddress(null, null, $contact->customer->address, $contact->customer->city, $contact->customer->state, $contact->customer->zip, null);

        //  Phone Numbers
        foreach ($contact->CustomerContactPhone as $phone) {
            $number = $phone->formatted;
            if ($phone->extension) {
                $number .= ', '.$phone->extension;
            }
            $vCard->addPhoneNumber($number, $phone->PhoneNumberType->description);
        }

        return $vCard;
    }
}
