<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerContactRequest;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Services\Customer\CustomerContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerContactController extends Controller
{
    public function __construct(protected CustomerContactService $svc) {}

    /**
     * Create a new Customer Contact
     */
    public function store(CustomerContactRequest $request, Customer $customer): RedirectResponse
    {
        $newContact = $this->svc
            ->createCustomerContact($request->safe()->collect(), $customer);

        return back()->with('success', __('cust.contact.created', [
            'cont' => $newContact->name,
        ]));
    }

    /**
     *
     */
    public function update(
        CustomerContactRequest $request,
        Customer $customer,
        CustomerContact $contact
    ): RedirectResponse {
        $updatedContact = $this->svc
            ->updateCustomerContact($request->safe()->collect(), $contact);

        return back()->with('success', __('cust.contact.updated', [
            'cont' => $updatedContact->name,
        ]));
    }

    /**
     * Soft delete a Customer Contact
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
     *
     */
    public function restore(string $id)
    {
        //
        return 'restore';
    }

    /**
     *
     */
    public function forceDelete(string $id)
    {
        //
        return 'force delete';
    }
}
