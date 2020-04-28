<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Domains\Customers\GetCustomerNotes;
use App\Domains\Customers\SetCustomerNotes;

use App\Http\Requests\CustomerNewNoteRequest;
use App\Http\Requests\CustomerEditNoteRequest;

class CustomerNotesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Store a new customer note
    public function store(CustomerNewNoteRequest $request)
    {
        (new SetCustomerNotes($request->cust_id))->createNote($request);
        return response()->json(['success' => true]);
    }

    //  Get the customer notes
    public function show($id)
    {
        return (new GetCustomerNotes($id))->execute();
    }

    //  Update a customer note
    public function update(CustomerEditNoteRequest $request, $id)
    {
        (new SetCustomerNotes($request->cust_id))->updateNote($request, $id);
        return response()->json(['success' => true]);
    }

    //  Delete a customer note
    public function destroy($id)
    {
        (new SetCustomerNotes($id))->deleteNote($id);
        return response()->json(['success' => true]);
    }
}
