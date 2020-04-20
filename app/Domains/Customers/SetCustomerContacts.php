<?php

namespace App\Domains\Customers;

use App\CustomerContactPhones;
use App\CustomerContacts;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Customers;
use App\Http\Requests\CustomerEditContactRequest;
use App\Http\Requests\CustomerNewContactRequest;

class SetCustomerContacts
{
    protected $custID;

    public function __construct($custID)
    {
        $this->custID = $custID;
    }

    public function createContact(CustomerNewContactRequest $request)
    {
        //
        if($request->shared)
        {
            $this->checkBelongToParent($request->cust_id);
        }

        //  Update the primary information
        $contData = CustomerContacts::create([
            'cust_id' => $this->custID,
            'shared'  => $request->shared,
            'name'    => $request->name,
            'email'   => $request->email,
        ]);

        $this->processPhoneNumbers($contData->cont_id, $request->customer_contact_phones);

        Log::info('Contact created for customer ID '.$this->custID.' by '.Auth::user()->full_name.'.  New Data - ', array($contData));
        return true;
    }

    public function updateContact(CustomerEditContactRequest $request)
    {
        //  verify that the contact id is valid
        $contData = CustomerContacts::where('cont_id', $request->cont_id)->where('cust_id', $this->custID)->first();
        if(!$contData)
        {
            return false;
        }

        if($request->shared)
        {
            $this->checkBelongToParent($request->cust_id);
        }

        //  Update the primary information
        $contData->update([
            'cust_id' => $this->custID,
            'shared'  => $request->shared,
            'name'    => $request->name,
            'email'   => $request->email,
        ]);

        $this->processPhoneNumbers($request->cont_id, $request->customer_contact_phones);

        Log::info('Contact information updated for customer ID '.$this->custID.' by '.Auth::user()->full_name.'.  New Data - ', array($contData));
        return true;
    }

    //  Remove a specific contact
    public function deleteContact($contID)
    {
        CustomerContacts::find($contID)->delete();
        Log::notice('Customer Contact ID '.$contID.' deleted by '.Auth::user()->full_name);
        return true;
    }

    //  Determine if the contact should be attached to the parent site
    protected function checkBelongToParent($custID)
    {
        $custData = Customers::find($custID);
        if($custData->parent_id)
        {
            $this->custID = $custData->parentID;
        }
    }

    //  Cycle through phone numbers and enter or edit them in the database
    protected function processPhoneNumbers($contID, $phoneData)
    {
        $curData = CustomerContactPhones::where('cont_id', $contID)->get();

        if($phoneData)
        {
            foreach($phoneData as $phone)
            {
                if(isset($phone['id']) && !empty($phone['readable']))
                {
                    CustomerContactPhones::find($phone['id'])->update([
                        'phone_type_id' => $phone['phone_type_id'],
                        'phone_number'  => $this->cleanPhoneNumber($phone['readable']),
                        'extension'     => $phone['extension'],
                    ]);
                    $curData = $curData->filter(function($item) use ($phone) {
                        return $item->id != $phone['id'];
                    });
                }
                else if(!empty($phone['readable']))
                {
                    CustomerContactPhones::create([
                        'cont_id'       => $contID,
                        'phone_type_id' => $phone['phone_type_id'],
                        'phone_number'  => $this->cleanPhoneNumber($phone['readable']),
                        'extension'     => $phone['extension'],
                    ]);
                }
            }
        }

        //  Remove any numbers that were deleted
        foreach($curData as $phone)
        {
            CustomerContactPhones::find($phone->id)->delete();
        }
    }

    //  Re-format the number to be strictly 10 digits
    protected function cleanPhoneNumber($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }

}
