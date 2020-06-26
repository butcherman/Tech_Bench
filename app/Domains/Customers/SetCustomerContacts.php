<?php

namespace App\Domains\Customers;

use App\CustomerContacts;
use App\CustomerContactPhones;

use Illuminate\Support\Facades\Log;

class SetCustomerContacts extends GetCustomerDetails
{
    //  Create a new contact for a customer
    public function createNewContact($request, $custID)
    {
        if($request->shared)
        {
            $parent = $this->getParentID($custID);
            if($parent)
            {
                $custID = $parent;
            }
        }

        $contID = $this->createContact($custID, $request->name, $request->email, $request->shared);
        $this->processNewPhoneNumbers($contID, $request->customer_contact_phones);

        return true;
    }

    //  Update a contact
    public function updateExistingContact($request, $contID)
    {
        $custID = $request->cust_id;
        if($request->shared)
        {
            $parent = $this->getParentID($custID);
            if($parent)
            {
                $custID = $parent;
            }
        }

        $this->updateContact($custID, $contID, $request->name, $request->email, $request->shared);
        $this->processExistingNumbers($contID, $request->customer_contact_phones);

        return true;
    }

    //  Remove a contact
    public function deleteContact($contID)
    {
        CustomerContacts::find($contID)->delete();
        return true;
    }

    protected function createContact($custID, $name, $email, $shared)
    {
        $newCont = CustomerContacts::create([
            'cust_id' => $custID,
            'name'    => $name,
            'email'   => $email,
            'shared'  => $shared,
        ]);

        return $newCont->cont_id;
    }

    public function updateContact($custID, $contID, $name, $email, $shared)
    {
        CustomerContacts::find($contID)->update([
            'cust_id' => $custID,
            'name'    => $name,
            'email'   => $email,
            'shared'  => $shared,
        ]);

        return true;
    }

    //  Add any new phone numbers to the contact
    protected function processNewPhoneNumbers($contID, $phoneData)
    {
        foreach($phoneData as $number)
        {
            $number = (object) $number;
            if($number->readable != null)
            {
                CustomerContactPhones::create([
                    'cont_id'       => $contID,
                    'phone_type_id' => $number->phone_type_id,
                    'phone_number'  => $this->cleanPhoneNumber($number->readable),
                    'extension'     => $number->extension,
                ]);
            }
        }
    }

    //  Update any existing phone numbers to the edited contact
    protected function processExistingNumbers($contID, $phoneData)
    {
        $curentData = CustomerContactPhones::where('cont_id', $contID)->get()->keyBy('id');
        $newData    = [];

        foreach($phoneData as $number)
        {
            $number = (object) $number;
            if(isset($number->id) && $number->readable != null)
            {
                $this->updateNumber($number->id, $number->phone_type_id, $this->cleanPhoneNumber($number->readable), $number->extension);
                $curentData->forget($number->id);
            }
            else
            {
                $newData[] = $number;
            }
        }

        $this->processNewPhoneNumbers($contID, $newData);
        $this->deletePhoneNumbers($curentData);

        return true;
    }

    protected function updateNumber($numID, $typeID, $number, $ext)
    {
        CustomerContactPhones::find($numID)->update([
            'phone_type_id' => $typeID,
            'phone_number'  => $number,
            'extension'     => $ext,
        ]);

        return true;
    }

    //  Remove a phone number from a contact
    protected function deletePhoneNumbers($delArray)
    {
        foreach($delArray as $del)
        {
            CustomerContactPhones::find($del->id)->delete();
        }

        return true;
    }

    //  Remove all extra charachters from the phone number.  Number should be stored as 10 digit direct number
    protected function cleanPhoneNumber($number)
    {
        $newNumber = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
        Log::debug('Phone number '.$number.' cleaned to '.$newNumber);
        return $newNumber;
    }
}
