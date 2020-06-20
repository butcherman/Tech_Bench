<?php

namespace App\Domains\Customers;

use App\CustomerContactPhones;
use App\CustomerContacts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class SetCustomerContacts extends GetCustomerDetails
{
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

    protected function deletePhoneNumbers($delArray)
    {
        foreach($delArray as $del)
        {
            CustomerContactPhones::find($del->id)->delete();
        }

        return true;
    }

    protected function cleanPhoneNumber($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}
