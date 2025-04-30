<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerContactRequest;
use App\Models\Customer;
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
    public function show(string $id)
    {
        //
        return 'show';
    }

    /**
     *
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     *
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     *
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
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
