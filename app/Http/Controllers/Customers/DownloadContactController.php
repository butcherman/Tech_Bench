<?php

namespace App\Http\Controllers\Customers;

use JeroenDesloovere\VCard\VCard;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Http\Controllers\Controller;
use App\Events\Customers\CustomerContactDownloadedEvent;

class DownloadContactController extends Controller
{
    /**
     * Download a customer contact in v-card format
     */
    public function __invoke($contID)
    {
        $contact  = CustomerContact::findOrFail($contID);
        $customer = Customer::find($contact->cust_id);
        $name     = explode(' ', $contact->name);

        //  Build the VCard
        $vcard = new VCard();
        $vcard->addName(isset($name[1]) ? $name[1] : null, $name[0]);
        $vcard->addCompany($customer->name);
        $vcard->addAddress(null, null, $customer->address, $customer->city, $customer->state, $customer->zip, null);

        //  Add phone numbers to the VCard
        foreach($contact->CustomerContactPhone as $phone)
        {
            $number = $phone->readable;
            if($phone->extension)
            {
                $number .= ', '.$phone->extension;
            }
            $vcard->addPhoneNumber($number, $phone->type_name);
        }

        event(new CustomerContactDownloadedEvent($customer, $contact));
        return $vcard->download();
    }
}
