<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\PhoneNumberTypes;
use App\CustomerContacts;
use Illuminate\Http\Request;
use App\CustomerContactPhones;
use App\Domains\Customers\GetCustomerContacts;
use App\Domains\Customers\GetCustomerDetails;
use App\Domains\Customers\SetCustomerContacts;
use JeroenDesloovere\VCard\VCard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerEditContactRequest;
use App\Http\Requests\CustomerNewContactRequest;
use Illuminate\Support\Facades\Route;

class CustomerContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Index funtion will only return the type of phone numbers that can be assigned to a customer
    public function index()
    {
        return (new GetCustomerContacts)->getPhoneNumberTypes(true);
    }

    //  Store a new customer contact
    public function store(CustomerNewContactRequest $request)
    {
        (new SetCustomerContacts($request->cust_id))->createContact($request);
        return response()->json(['success' => true]);
    }

    //  Get the contacts for a customer
    public function show($id)
    {
        return (new GetCustomerContacts($id))->execute();
    }

    //  Edit function will actually download the contact information in V-Card format
    public function edit($id)
    {
        $contData = (new GetCustomerContacts)->getOneContact($id);
        $custData = (new GetCustomerDetails)->getDetails($contData['cust_id']);


        $vcard = new VCard();
        $vcard->addName($contData['lastName'], $contData['firstName'], $contData['additional'], $contData['prefix'], $contData['suffix']);
        $vcard->addCompany($custData['name']);
        $vcard->addEmail($contData['email']);
        $vcard->addAddress(null, null, $custData['address'], $custData['city'], $custData['state'], $custData['zip'], null);
        if(!empty($contData['numbers']))
        {
            foreach($contData['numbers'] as $phone)
            {
                $vcard->addPhoneNumber($phone->phone_number, $phone->description);
            }
        }
        return $vcard->download();
    }

    //  Update an existing Customer Contact
    public function update(CustomerEditContactRequest $request, $id)
    {
        (new SetCustomerContacts($id))->updateContact($request);
        return response()->json(['success' => true]);
    }

    //  Delete an existing contact
    public function destroy($id)
    {
        (new SetCustomerContacts($id))->deleteContact($id);
        return response()->json(['success' => true]);
    }
}
