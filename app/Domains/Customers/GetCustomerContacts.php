<?php

namespace App\Domains\Customers;

use App\CustomerContacts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JeroenDesloovere\VCard\VCard;

class GetCustomerContacts extends GetCustomerDetails
{
    public function execute($custID)
    {
        $contacts = $this->getAllContacts($custID);

        //  Get any contacts that are shared between sites
        if($parent = $this->getParentID($custID))
        {
            $contacts = $contacts->merge($this->getAllContacts($parent, true));
        }

        return $contacts;
    }

    public function getOneContact($contID)
    {
        $cont = CustomerContacts::where('cont_id', $contID)->with('CustomerContactPhones')->first();
        $cust = $this->getDetails($cont->cust_id);
        $name = explode(' ', $cont->name);

        //  Build the VCard
        $vcard = new VCard();
        $vcard->addName(isset($name[1]) ? $name[1] : null, $name[0]);
        $vcard->addCompany($cust->name);
        $vcard->addAddress(null, null, $cust->address, $cust->city, $cust->state, $cust->zip, null);

        //  Add phone numbers to the VCard
        foreach($cont->CustomerContactPhones as $phone)
        {
            $number = $phone->readable;
            if($phone->extension)
            {
                $number .= ', '.$phone->extension;
            }
            $vcard->addPhoneNumber($number, $phone->type_name);
        }

        return $vcard;
    }

    protected function getAllContacts($custID, $shared = false)
    {
        return CustomerContacts::where('cust_id', $custID)
            ->when($shared, function($q)
            {
                $q->where('shared', 1);
            })
            ->with('CustomerContactPhones')
            ->get();
    }
}
