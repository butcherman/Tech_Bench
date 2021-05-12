<?php

namespace App\Http\Controllers\Customers;

use App\Models\CustomerNote;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerNoteRequest;

class CustomerNoteController extends Controller
{
    /**
     *  Store a new customer note
     */
    public function store(CustomerNoteRequest $request)
    {
        CustomerNote::create([
            'cust_id'    => $request->cust_id,
            'created_by' => $request->user()->user_id,
            'urgent'     => $request->urgent,
            'shared'     => $request->shared,
            'subject'    => $request->subject,
            'details'    => $request->details,
        ]);

        return response()->noContent();
    }

    /**
     *  Get all of the notes for a customer
     */
    public function show($id)
    {
        return CustomerNote::where('cust_id', $id)->get();
    }

    /**
     *  Modify an existing note
     */
    public function update(CustomerNoteRequest $request, $id)
    {
        CustomerNote::find($id)->update([
            'updated_by' => $request->user()->user_id,
            'urgent'     => $request->urgent,
            'shared'     => $request->shared,
            'subject'    => $request->subject,
            'details'    => $request->details,
        ]);

        return response()->noContent();
    }

    /**
     *  Delete a customer Note
     */
    public function destroy($id)
    {
        CustomerNote::find($id)->delete();

        return response()->noContent();
    }
}
