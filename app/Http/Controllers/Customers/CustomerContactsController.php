<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\PhoneNumberType;
use App\CustomerContacts;
use Illuminate\Http\Request;
use App\CustomerContactsView;
use App\CustomerContactPhones;
use JeroenDesloovere\VCard\VCard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class CustomerContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Store a new customer contact
    public function store(Request $request)
    {
        $request->validate([
            'custID' => 'required',
            'name'   => 'required'
        ]);
        
        $cont = CustomerContacts::create([
            'cust_id' => $request->custID,
            'name'    => $request->name,
            'email'   => $request->email
        ]);
        
        $contID = $cont->cont_id;
        
        foreach($request->numbers['type'] as $key => $num)
        {
            if(!empty($request->numbers['number'][$key]))
            {
                CustomerContactPhones::create([
                    'cont_id'       => $contID,
                    'phone_type_id' => $request->numbers['type'][$key],
                    'phone_number'  => PhoneNumberType::cleanPhoneNumber($request->numbers['number'][$key]),
                    'extension'     => isset($request->numbers['ext'][$key]) ? $request->numbers['ext'][$key] : null
                ]);
            }
        }
        
//        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
//        Log::debug('Submitted Data - ', $request->toArray());
        Log::info('New Customer Contact Created for Cust ID - '.$request->custID.'.  Contact ID-'.$contID);
        return response()->json(['success' => true]);
    }

    //  Get the contacts for a customer
    public function show($id)
    {
        $contacts = CustomerContacts::where('cust_id', $id)->with('CustomerContactsView')->get();
        
        $contArr = [];
        foreach($contacts as $cont)
        {
            $phoneNum = [];
            foreach($cont->CustomerContactsView as $num)
            {
                $phoneNum[] = [
                    'class'         => $num->icon_class,
                    'type'          => $num->description,
                    'type_id'       => $num->phone_type_id,
                    'number'        => $num->phone_number,
                    'num_formatted' => PhoneNumberType::readablePhoneNumber($num->phone_number),
                    'ext'           => $num->extension
                ];
            }
            
            $contArr[] = [
                'cont_id' => $cont->cont_id,
                'name'    => $cont->name,
                'numbers' => $phoneNum,
                'email'   => $cont->email
            ];
        }
        
//        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
//        Log::debug('Fetched Data - ', $contacts->toArray());
        return response()->json($contArr);
    }

    //  Edit function will actually download the contact information in V-Card format
    public function edit($id)
    {
        $contact  = CustomerContacts::find($id);
        $numbers  = CustomerContactsView::where('cont_id', $id)->get();
        $custData = Customers::find($contact->cust_id);
        
        $contactName = explode(' ', $contact->name);
        $firstName   = $contactName[0];
        $lastName    = isset($contactName[1]) ? $contactName[1] : '';
        $additional  = '';
        $prefix      = '';
        $suffix      = '';
        
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
        
//        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::info('Customer Contact Downloaded - Contact ID-'.$id);
        
        return $vcard->download();
    }

    //  Update an existing Customer Contact
    public function update(Request $request, $id)
    {
        $request->validate([
            'custID' => 'required',
            'name'   => 'required'
        ]);
        
        CustomerContacts::find($id)->update([
            'name'    => $request->name,
            'email'   => $request->email
        ]);
        
        $contID = $id;
        
        //  Clear all contact phone numbers and re-enter them
        CustomerContactPhones::where('cont_id', $id)->delete();
        foreach($request->numbers['type'] as $key => $num)
        {
            if(!empty($request->numbers['number'][$key]))
            {
                CustomerContactPhones::create([
                    'cont_id'       => $contID,
                    'phone_type_id' => $request->numbers['type'][$key],
                    'phone_number'  => PhoneNumberType::cleanPhoneNumber($request->numbers['number'][$key]),
                    'extension'     => isset($request->numbers['ext'][$key]) ? $request->numbers['ext'][$key] : null
                ]);
            }
        }
        
//        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
//        Log::debug('Submitted Data - ', $request->toArray());
        Log::info('Customer Contact Updated for Cust ID - '.$request->custID.'.  Contact ID-'.$contID);
        return response()->json(['success' => true]);
    }

    //  Delete an existing contact
    public function destroy($id)
    {
        $cont = CustomerContacts::find($id);
        
//        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::info('Customer Contact deleted for Customer ID-'.$cont->cust_id.' by User ID-'.Auth::user()->user_id.'. Deleted Contact ID-'.$id);
        
        $cont->delete();
        
        return response()->json(['success' => true]);
    }
}
