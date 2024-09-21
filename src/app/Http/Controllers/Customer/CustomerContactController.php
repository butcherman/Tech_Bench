<?php

namespace App\Http\Controllers\Customer;

use App\Enum\CrudAction;
use App\Events\Customer\CustomerContactEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerContactRequest;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerContactController extends Controller
{
    /**
     * Store a newly created Customer Contact in storage.
     */
    public function store(CustomerContactRequest $request, Customer $customer): RedirectResponse
    {
        $newContact = $request->createContact();

        Log::info(
            'New Customer Contact created for '.
            $customer->name.' by '.$request->user()->username,
            $newContact->toArray()
        );

        event(new CustomerContactEvent($customer, $newContact, CrudAction::Create));

        return back()->with('success', __('cust.contact.created', [
            'cont' => $newContact->name,
        ]));
    }

    /**
     * Update the specified Customer Contact in storage.
     */
    public function update(
        CustomerContactRequest $request,
        Customer $customer,
        CustomerContact $contact
    ): RedirectResponse {
        $updatedContact = $request->updateContact();

        Log::info(
            'Customer Contact updated for '.
            $customer->name.' by '.$request->user()->username,
            $updatedContact->toArray()
        );

        event(new CustomerContactEvent($customer, $updatedContact, CrudAction::Update));

        return back()->with('success', __('cust.contact.updated', [
            'cont' => $updatedContact->name,
        ]));
    }

    /**
     * Remove the specified Customer Contact from storage.
     */
    public function destroy(
        Request $request,
        Customer $customer,
        CustomerContact $contact
    ): RedirectResponse {
        $this->authorize('delete', $contact);

        $contact->delete();

        Log::notice(
            'Customer Contact deleted for '.
            $customer->name.' by '.$request->user()->username,
            $contact->toArray()
        );

        event(new CustomerContactEvent($customer, $contact, CrudAction::Destroy));

        return back()->with('warning', __('cust.contact.deleted', [
            'cont' => $contact->name,
        ]));
    }

    /**
     * Restore a soft deleted contact
     */
    public function restore(
        Request $request,
        Customer $customer,
        CustomerContact $contact
    ): RedirectResponse {
        $this->authorize('restore', $contact);

        $contact->restore();

        Log::info('Customer Contact restored for '.$customer->name.' by '.
                $request->user()->username, $contact->toArray());

        event(new CustomerContactEvent($customer, $contact, CrudAction::Restore));

        return back()->with('success', __('cust.contact.restored', [
            'cont' => $contact->name,
        ]));
    }

    /**
     * remove a soft deleted contact
     */
    public function forceDelete(
        Request $request,
        Customer $customer,
        CustomerContact $contact
    ): RedirectResponse {

        $this->authorize('force-delete', $contact);

        $contact->forceDelete();

        Log::notice('Customer Contact force deleted for '.$customer->name.
                ' by '.$request->user()->username, $contact->toArray());

        event(new CustomerContactEvent($customer, $contact, CrudAction::ForceDelete));

        return back()
            ->with('warning', __('cust.contact.force_deleted', [
                'cont' => $contact->name,
            ]));
    }
}
