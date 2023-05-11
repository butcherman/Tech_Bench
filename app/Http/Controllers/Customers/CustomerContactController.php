<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerContactRequest;
use App\Models\CustomerContact;
use Illuminate\Support\Facades\Log;

class CustomerContactController extends Controller
{
    /**
     * Display a listing of the resource.Redirecting back to customer page will refresh the contact list
     */
    public function index()
    {
        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerContactRequest $request)
    {
        $request->checkForShared();
        $newContact = CustomerContact::create($request->only(['cust_id', 'shared', 'name', 'email', 'title', 'note']));
        $request->processPhoneNumbers($newContact->cont_id);

        Log::info('New Customer contact created for Customer ID '.$request->input('cust_id').' by '.$request->user()->full_name);

        return back()->with('success', 'Contact Created');
    }

    /**
     * Update the specified Contact
     */
    public function update(CustomerContactRequest $request, CustomerContact $contact)
    {
        $request->checkForShared();
        $contact->update($request->toArray());
        $request->processPhoneNumbers($contact->cont_id, true);

        return back()->with('success', 'Contact Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerContact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();

        return back()->with('warning', 'Contact Deleted');
    }

    /**
     * Restore a soft deleted item
     */
    public function restore(CustomerContact $contact)
    {
        $this->authorize('restore', $contact);

        $contact->restore();

        return back()->with('success', 'Contact Restored');
    }

    /**
     * Force delete contact forever
     */
    public function forceDelete(CustomerContact $contact)
    {
        $this->authorize('forceDelete', $contact);

        $contact->forceDelete();

        return back()->with('danger', 'Customer Contact Deleted');
    }
}
