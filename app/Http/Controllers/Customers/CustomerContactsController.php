<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\PhoneNumberType;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Events\Customers\CustomerContactAddedEvent;
use App\Events\Customers\CustomerContactDeletedEvent;
use App\Events\Customers\CustomerContactUpdatedEvent;
use App\Http\Requests\Customers\CustomerContactsRequest;
use Inertia\Inertia;

class CustomerContactsController extends Controller
{
    /**
     * Store a newly created customer contact
     */
    public function store(CustomerContactsRequest $request)
    {
        $cust    = Customer::findOrFail($request->cust_id);
        $cust_id = $cust->cust_id;

        //  If the equipment is shared, it must be assigned to the parent site
        if($request->shared && $cust->parent_id > 0)
        {
            $cust_id = $cust->parent_id;
        }

        //  Create the contact
        $newContact = CustomerContact::create([
            'cust_id' => $cust_id,
            'name'    => $request->name,
            'email'   => $request->email,
            'shared'  => $request->shared,
            'title'   => $request->title,
            'note'    => $request->note,
        ]);

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

        event(new CustomerContactAddedEvent($cust, $newContact));
        return back()->with(['message' => 'New Contact Created', 'type' => 'success']);
    }

    /**
     * Update an existing contact
     */
    public function update(CustomerContactsRequest $request, $id)
    {
        $cust    = Customer::findOrFail($request->cust_id);
        $cust_id = $cust->cust_id;

        //  If the equipment is shared, it must be assigned to the parent site
        if($request->shared && $cust->parent_id > 0)
        {
            $cust_id = $cust->parent_id;
        }

        $contact = CustomerContact::find($id);
        $contact->update([
            'cust_id' => $cust_id,
            'name'    => $request->name,
            'email'   => $request->email,
            'shared'  => $request->shared,
            'title'   => $request->title,
            'note'    => $request->note,
        ]);

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

        event(new CustomerContactUpdatedEvent($cust, $contact));
        return back()->with(['message' => 'Contact Updated', 'type' => 'success']);
    }

    /**
     * Soft Delete a Customer Contact
     */
    public function destroy($id)
    {
        $this->authorize('delete', CustomerContact::class);
        $cont = CustomerContact::find($id);
        $cont->delete();

        event(new CustomerContactDeletedEvent(Customer::find($cont->cust_id), $cont));
        return back()->with(['message' => 'Contact deleted', 'type' => 'danger']);
    }

    /*
    *   Clean the phone number to be digits only
    */
    protected function cleanPhoneNumber($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}
