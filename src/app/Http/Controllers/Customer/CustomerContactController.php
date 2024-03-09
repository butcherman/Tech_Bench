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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return 'index';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return 'create';
    }

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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
