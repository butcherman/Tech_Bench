<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JeroenDesloovere\VCard\VCard;
use App\Customers;
use App\CustomerContactPhones;
use App\CustomerContacts;
use App\CustomerContactsView;
use App\PhoneNumberType;

class CustomerContactsController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Open the new contact form
    public function create()
    {
        $numberTypes = PhoneNumberType::all();
        
        $numTypes = [];
        foreach($numberTypes as $type)
        {
            $numTypes[$type->phone_type_id] = $type->description;
        }

        return view('customer.form.newContact', [
            'numberTypes' => $numTypes
        ]);
    }

    //  Submit a new contact
    public function store(Request $request)
    {
        $request->validate(['custID' => 'required', 'name' => 'required']);
        
        $cont = CustomerContacts::create([
            'cust_id' => $request['custID'],
            'name'    => $request['name'],
            'email'   => $request['email']
        ]);
        
        $contID = $cont->cont_id;
        
        if(!empty(array_filter($request['phoneNumber'])))
        {
            for($i=0; $i<count($request['phoneNumber']); $i++)
            {
                if(!empty($request['phoneNumber'][$i]))
                {
                    CustomerContactPhones::create([
                        'cont_id' => $contID,
                        'phone_type_id' => $request['numType'][$i],
                        'phone_number'  => PhoneNumberType::cleanPhoneNumber($request['phoneNumber'][$i]),
                        'extension'     => $request['extension'][$i]
                    ]);
                }
            }
        }
        
        Log::info('Customer Contact Created', ['cust_id' => $request['custID'], 'cont_id' => $contID, 'user_id' => Auth::user()->user_id]);
    }

    //Show all customer contacts
    public function show($id)
    {
        $contacts = CustomerContacts::where('cust_id', $id)->with('CustomerContactsView')->get();
        
        return view('customer.contacts', [
            'contacts' => $contacts
        ]);
    }

    //  Open the edit Contact form
    public function edit($id)
    {
        $contact = CustomerContacts::find($id);
        $numbers = CustomerContactsView::where('cont_id', $id)->get();
        $numberTypes = PhoneNumberType::all();
        
        $numTypes = [];
        foreach($numberTypes as $type)
        {
            $numTypes[$type->phone_type_id] = $type->description;
        }
        
        return view('customer.form.editContact',
        [
            'contID'      => $id,
            'contact'     => $contact,
            'numberTypes' => $numTypes,
            'numbers'     => $numbers
        ]);
    }

    //  Update the customer contact
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        
        $cont = CustomerContacts::where('cont_id', $id)->update([
            'name'  => $request['name'],
            'email' => $request['email']
        ]);
        
        //  Clear all contact phone numbers and re-enter them
        CustomerContactPhones::where('cont_id', $id)->delete();
        if(!empty(array_filter($request['phoneNumber'])))
        {
            for($i=0; $i<count($request['phoneNumber']); $i++)
            {
                CustomerContactPhones::create([
                    'cont_id'       => $id,
                    'phone_type_id' => $request['numType'][$i],
                    'phone_number'  => PhoneNumberType::cleanPhoneNumber($request['phoneNumber'][$i]),
                    'extension'     => $request['extension'][$i]
                ]);
            }
        }
        
        Log::info('Customer Contact Updated', ['cont_id' => $contID, 'user_id' => Auth::user()->user_id]);
    }

    //  Delete customer contact
    public function destroy($id)
    {
        $cont = CustomerContacts::find($id);
        
        Log::info('Customer Contat Deleted', ['cont_id' => $id, 'cust_id' => $cont->cust_id, 'user_id' => Auth::user()->user_id]);
    }
    
    //  Download the contact informaiton as a V-Card
    public function downloadVCard($id)
    {
        $contact = CustomerContacts::find($id);
        $numbers = CustomerContactsView::where('cont_id', $id)->get();
        $custData = Customers::find($contact->cust_id);
        
        $contactName = explode(' ', $contact->name);
        $firstName = $contactName[0];
        $lastName = isset($contactName[1]) ? $contactName[1] : '';
        $additional = '';
        $prefix = '';
        $suffix = '';
        
        $vcard = new VCard();
        $vcard->addName($lastName, $firstName, $additional, $prefix, $suffix);
        $vcard->addCompany($custData->name);
        $vcard->addEmail($contact->email);
        $vcard->addAddress(null, null, $custData->address, $custData->city, $custData->state, $custData->zip, null);
        
        if(!empty($numbers))
        {
            foreach($numbers as $phone)
            {
                $vcard->addPhoneNumber($phone->phone_number, $phone->description);
            }
        }
        
        return $vcard->download();
    }
}
