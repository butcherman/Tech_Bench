<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerContactRequest;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use Illuminate\Http\Request;

class CustomerContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     *  Create a new contact
     */
    public function store(CustomerContactRequest $request)
    {
        //  Create the contact
        $newContact = CustomerContact::create($request->only(['cust_id', 'name', 'email', 'shared']));

        //  Input the contacts phone numbers
        foreach($request->phones as $phone)
        {
            if(isset($phone['number']))
            {
                CustomerContactPhone::create([
                    'cont_id'       => $newContact->cont_id,
                    'phone_type_id' => PhoneNumberType::where('description', $phone['type'])->first()->phone_type_id,
                    'phone_number'  => $this->cleanPhoneNumber($phone['number']),
                    'extension'     => $phone['extension'],
                ]);
            }
        }

        return back()->with(['message' => 'Contact Created', 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*
    *   Clean the phone number to be digits only
    */
    protected function cleanPhoneNumber($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}
