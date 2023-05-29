<?php

namespace App\Http\Controllers\Customers;

use App\Events\Customer\CustomerContactCreatedEvent;
use App\Events\Customer\CustomerContactUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerContactRequest;
use App\Models\CustomerContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerContactRequest $request)
    {
        $request->checkForShared();
        $newContact = CustomerContact::create($request->only(['cust_id', 'shared', 'name', 'email', 'title', 'note']));
        $request->processPhoneNumbers($newContact->cont_id);

        Log::stack(['daily', 'cust'])->info('New Customer contact created for Customer '.$newContact->Customer->name.' by '.$request->user()->full_name);
        event(new CustomerContactCreatedEvent($newContact->Customer, $newContact, $request->user()));

        return back()->with('success', __('cust.contact.created', ['cont' => $newContact->name]));
    }

    /**
     * Update the specified Contact
     */
    public function update(CustomerContactRequest $request, CustomerContact $contact)
    {
        $request->checkForShared();
        $contact->update($request->toArray());
        $request->processPhoneNumbers($contact->cont_id, true);

        Log::stack(['daily', 'cust'])->info('Customer contact updated for Customer '.$contact->Customer->name.' by '.$request->user()->full_name, $contact->toArray());
        event(new CustomerContactUpdatedEvent($contact->Customer, $contact, $request->user()));

        return back()->with('success', __('cust.contact.updated', ['cont' => $contact->name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerContact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();
        Log::stack(['daily', 'cust'])->notice('Customer Contact '.$contact->name.' for '.$contact->Customer->name.' deleted by '.Auth::user()->username);

        return back()->with('warning', __('cust.contact.deleted', ['cont' => $contact->name]));
    }

    /**
     * Restore a soft deleted contact
     */
    public function restore(CustomerContact $contact)
    {
        $this->authorize('restore', $contact);

        $contact->restore();
        Log::stack(['daily', 'cust'])->info('Customer Contact '.$contact->name.' for '.$contact->Customer->name.' restored by '.Auth::user()->username);

        return back()->with('success', __('cust.contact.restored', ['cont' => $contact->name]));
    }

    /**
     * Force delete contact forever
     */
    public function forceDelete(CustomerContact $contact)
    {
        $this->authorize('forceDelete', $contact);

        $contact->forceDelete();
        Log::stack(['daily', 'cust'])->info('Customer Contact '.$contact->name.' for '.$contact->Customer->name.' permanently deleted by '.Auth::user()->username);

        return back()->with('danger', __('cust.contact.force_deleted', ['cont' => $contact->name]));
    }
}
