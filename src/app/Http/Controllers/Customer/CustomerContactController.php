<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerContactRequest;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Service\Customer\CustomerContactService;
use Illuminate\Http\RedirectResponse;

class CustomerContactController extends Controller
{
    public function __construct(protected CustomerContactService $svc) {}

    /**
     * Store a newly created Customer Contact in storage.
     */
    public function store(CustomerContactRequest $request, Customer $customer): RedirectResponse
    {
        $newContact = $this->svc->createCustomerContact($request, $customer);

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
        $updatedContact = $this->svc->updateCustomerContact($request, $contact);

        return back()->with('success', __('cust.contact.updated', [
            'cont' => $updatedContact->name,
        ]));
    }

    /**
     * Remove the specified Customer Contact from storage.
     */
    public function destroy(Customer $customer, CustomerContact $contact): RedirectResponse
    {
        $this->authorize('delete', $contact);

        $this->svc->destroyContact($contact);

        return back()->with('warning', __('cust.contact.deleted', [
            'cont' => $contact->name,
        ]));
    }

    /**
     * Restore a soft deleted contact
     */
    public function restore(Customer $customer, CustomerContact $contact): RedirectResponse
    {
        $this->authorize('restore', $contact);

        $this->svc->restoreContact($contact);

        return back()->with('success', __('cust.contact.restored', [
            'cont' => $contact->name,
        ]));
    }

    /**
     * remove a soft deleted contact
     */
    public function forceDelete(Customer $customer, CustomerContact $contact): RedirectResponse
    {

        $this->authorize('force-delete', $contact);

        $this->svc->destroyContact($contact, true);

        return back()
            ->with('warning', __('cust.contact.force_deleted', [
                'cont' => $contact->name,
            ]));
    }
}
