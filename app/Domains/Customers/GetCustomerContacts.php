<?php

namespace App\Domains\Customers;

use App\CustomerContacts;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Customers;
use App\Http\Resources\PhoneNumberTypesCollection;
use App\PhoneNumberTypes;

class GetCustomerContacts
{
    protected $custID;

    public function __construct($custID = null)
    {
        $this->custID = $custID;
    }

    public function execute()
    {
        $hasParent = Customers::findOrFail($this->custID)->parent_id;
        $localContacts = $this->getLocalContacts();

        if($hasParent)
        {
            $parentContacts = $this->getParentContacts($hasParent);
            return $localContacts->merge($parentContacts);
        }
        return $localContacts;
    }

    //  Pull a specific contact
    public function getOneContact($contID)
    {
        $contact        = CustomerContacts::where('cont_id', $contID)->with('CustomerContactPhones')->first();
        $contactName    = explode(' ', $contact->name);
        $contactDetails = collect((object) [
            'firstName'   => $contactName[0],
            'lastName'    => isset($contactName[1]) ? $contactName[1] : '',
            'email'       => $contact->eamil,   //  FIXME:  is this broken???
            'additional'  => '',
            'prefix'      => '',
            'suffix'      => '',
            'cust_id'     => $contact->cust_id,
            'numbers'     => $contact->CustomerContactPhones,
        ]);

        // return $contact;
        return $contactDetails;
    }

    //  Get the type of phone numbers that can be assigned to a customer
    public function getPhoneNumberTypes($collection = false)
    {
        $numberTypes = PhoneNumberTypes::all();
        Log::debug('Phone number type list gathered.  Gathered data - ', array($numberTypes));
        if($collection)
        {
            return new PhoneNumberTypesCollection($numberTypes);
        }

        return $numberTypes;
    }

    //  Retrieve any contacts attached to the customer
    protected function getLocalContacts()
    {
        $contacts = CustomerContacts::where('cust_id', $this->custID)
                        ->with('CustomerContactPhones')
                        ->get();

        Log::debug('Customer Contacts Query completed for customer ID '.$this->custID.'.  Results - ', array($contacts));
        return $contacts;
    }

    //  Retrieve any equipment attached to the parent customer
    protected function getParentContacts($parentID)
    {
        $parentList = CustomerContacts::where('cust_id', $parentID)
                        ->where('shared', 1)
                        ->with('CustomerContactPhones')
                        ->get();

        Log::debug('Customer Parent Contacts Query completed for Customer ID '.$this->custID.'.  Results - ', array($parentList));
        return $parentList;
    }
}
