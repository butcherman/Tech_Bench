<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerContactRequest;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use Illuminate\Http\Request;

class CustomerContactsController extends Controller
{
    /**
     *  Create a new contact
     */
    public function store(CustomerContactRequest $request)
    {
        //  Create the contact
        $newContact = CustomerContact::create($request->only(['cust_id', 'name', 'email', 'shared']));

        //  Input the contacts phone numbers
        foreach($request->phones as $phone)
        {
            if(isset($phone['number']))
            {
                CustomerContactPhone::create([
                    'cont_id'       => $newContact->cont_id,
                    'phone_type_id' => PhoneNumberType::where('description', $phone['type'])->first()->phone_type_id,
                    'phone_number'  => $this->cleanPhoneNumber($phone['number']),
                    'extension'     => $phone['extension'],
                ]);
            }
        }

        return back()->with(['message' => 'Contact Created', 'type' => 'success']);
    }

    /**
     *  Ajax call to get the contacts for a customer
     */
    public function show($id)
    {
        return CustomerContact::where('cust_id', $id)->with('CustomerContactPhone.PhoneNumberType')->get();
    }

    /**
     *  Update an existing contact
     */
    public function update(CustomerContactRequest $request, $id)
    {
        CustomerContact::find($id)->update($request->only(['cust_id', 'name', 'email', 'shared']));

        $updatedNumbers = [];
        foreach($request->phones as $phone)
        {
            //  If the number is an existing number, update it
            if(isset($phone['id']))
            {
                CustomerContactPhone::find($phone['id'])->update([
                    'phone_type_id' => PhoneNumberType::where('description', $phone['phone_number_type']['description'])->first()->phone_type_id,
                    'phone_number'  => $this->cleanPhoneNumber($phone['phone_number']),
                    'extension'     => $phone['extension'],
                ]);
                $updatedNumbers[] = $phone['id'];
            }
            //  Otherwise enter a new number
            else
            {
                $new = CustomerContactPhone::create([
                    'cont_id'       => $id,
                    'phone_type_id' =>PhoneNumberType::where('description', $phone['phone_number_type']['description'])->first()->phone_type_id,
                    'phone_number'  => $this->cleanPhoneNumber($phone['phone_number']),
                    'extension'     => $phone['extension'],
                ]);
                $updatedNumbers[] = $new->id;
            }
        }

        $oldContacts = CustomerContactPhone::where('cont_id', $id)->whereNotIn('id', $updatedNumbers)->get();
        foreach($oldContacts as $cont)
        {
            $cont->delete();
        }

        return back()->with(['message' => 'Contact Updated', 'type' => 'success']);
    }

    /**
     *  Delete a contact
     */
    public function destroy($id)
    {
        CustomerContact::findOrFail($id)->delete();
        return response()->noContent();
    }

    /*
    *   Clean the phone number to be digits only
    */
    protected function cleanPhoneNumber($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}
