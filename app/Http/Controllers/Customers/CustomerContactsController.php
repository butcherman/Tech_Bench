<?php

namespace App\Http\Controllers\Customers;

use App\Models\PhoneNumberType;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerContactRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerContactsController extends Controller
{
    /**
     *  Create a new contact
     */
    public function store(CustomerContactRequest $request)
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
        $cust    = Customer::findOrFail($request->cust_id);
        $cust_id = $cust->cust_id;

        //  If the equipment is shared, it must be assigned to the parent site
        if($request->shared && $cust->parent_id > 0)
        {
            $cust_id = $cust->parent_id;
        }

        CustomerContact::find($id)->update([
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

        return back()->with(['message' => 'Contact Updated', 'type' => 'success']);
    }

    /**
     *  Delete a contact
     */
    public function destroy($id)
    {
        $this->authorize('delete', CustomerContact::class);

        CustomerContact::findOrFail($id)->delete();
        return response()->noContent();
    }


    public function restore($id)
    {
        $this->authorize('restore', CustomerContact::class);
        $cont = CustomerContact::withTrashed()->where('cont_id', $id)->first();
        $cont->restore();

        Log::channel('cust')->info('Customer Contact '.$cont->cont_id.' was restored for Customer ID '.$cont->cust_id.' by '.Auth::user()->username);
        return redirect()->back()->with(['message' => 'Contact '.$cont->name.' restored', 'type' => 'success']);
    }

    public function forceDelete($id)
    {
        $this->authorize('forceDelete', CustomerContact::class);

        $cont = CustomerContact::withTrashed()->where('cont_id', $id)->first();

        Log::channel('cust')->alert('Customer Contact '.$cont->name.' has been permanently deleted by '.Auth::user()->username);
        $cont->forceDelete();

        return redirect()->back()->with(['message' => 'Contact permanently deleted', 'type' => 'danger']);
    }

    /*
    *   Clean the phone number to be digits only
    */
    protected function cleanPhoneNumber($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}
