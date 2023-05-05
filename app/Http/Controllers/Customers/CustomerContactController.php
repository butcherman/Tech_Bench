<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerContactRequest;
use App\Models\CustomerContact;
use Illuminate\Http\Request;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerContactRequest $request)
    {
        $newContact = CustomerContact::create($request->only(['cust_id', 'shared', 'name', 'email', 'title', 'note']));
        $request->addPhoneNumbers($newContact->cont_id);

        Log::info('New Customer contact created for Customer ID '.$request->input('cust_id').' by '.$request->user()->full_name);

        return back()->with('success', 'Contact Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
