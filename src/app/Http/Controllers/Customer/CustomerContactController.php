<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerContactRequest;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerContactRequest $request, Customer $customer)
    {
        $newContact = $request->createContact();

        Log::channel('cust')->info(
            'New Customer Contact created for ' .
            $customer->name . ' by ' . $request->user()->username,
            $newContact->toArray()
        );

        return back()->with('success', __('cust.contact.created', [
            'cont' => $newContact->name
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerContactRequest $request, Customer $customer, CustomerContact $contact)
    {
        $updatedContact = $request->updateContact();

        Log::channel('cust')->info(
            'Customer Contact updated for ' .
            $customer->name . ' by ' . $request->user()->username,
            $updatedContact->toArray()
        );

        return back()->with('success', __('cust.contact.updated', [
            'cont' => $updatedContact->name
        ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Customer $customer, CustomerContact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();

        Log::channel('cust')->notice(
            'Customer Contact deleted for ' .
            $customer->name . ' by ' . $request->user()->username,
            $contact->toArray()
        );

        return back()->with('warning', __('cust.contact.deleted', [
            'cont' => $contact->name
        ]));
    }
}
