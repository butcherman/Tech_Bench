<?php

namespace App\Http\Controllers\Customers;

use App\Domains\Customers\GetCustomerContacts;
use App\Domains\Customers\SetCustomerContacts;
use App\Domains\PhoneNumbers\GetPhoneNumberTypes;

use App\Http\Controllers\Controller;

use App\Http\Requests\Customers\CustomerContactRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerContactsController extends Controller
{
    //  Get the types of phone numbers that can be assigned to a contact
    public function index()
    {
        return (new GetPhoneNumberTypes)->execute();
    }

    //  Store a new customer contact
    public function store(CustomerContactRequest $request)
    {
        (new SetCustomerContacts)->createNewContact($request, $request->cust_id);
        return response()->json(['success' => true]);
    }

    //  Pull all contacts for the customer
    public function show($id)
    {
        return (new GetCustomerContacts)->execute($id);
    }

    //  Downlod a specific contact as a V-Card
    public function download($id)
    {
        $contact = (new GetCustomerContacts)->getOneContact($id);
        return $contact->download();
    }

    //  Update an existing contact
    public function update(CustomerContactRequest $request, $id)
    {
        (new SetCustomerContacts)->updateExistingContact($request, $id);
        return response()->json(['success' => true]);
    }

    //  Delete a contact
    public function destroy($id)
    {
        (new SetCustomerContacts)->deleteContact($id);
        Log::notice('Customer Contact ID '.$id.' deleted by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
