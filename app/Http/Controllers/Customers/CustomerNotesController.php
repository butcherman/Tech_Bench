<?php

namespace App\Http\Controllers\Customers;

use App\Domains\Customers\GetCustomerNotes;
use App\Domains\Customers\SetCustomerNotes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerNoteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerNotesController extends Controller
{
    //  Create a new customer note
    public function store(CustomerNoteRequest $request)
    {
        (new SetCustomerNotes)->createNewNote($request, $request->cust_id, Auth::user()->user_id);
        Log::info('A new note has been created for Customer ID '.$request->cust_id.' by '.Auth::user()->full_name.'.  Data - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    //  Get all notes for a customer
    public function show($id)
    {
        return (new GetCustomerNotes)->execute($id);
    }

    //  Update an existing note
    public function update(Request $request, $id)
    {
        (new SetCustomerNotes)->updateNote($request, $request->cust_id, $id);
        Log::info('Customer Note ID '.$id.' updated by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }

    //  Delete an existing note
    public function destroy($id)
    {
        (new SetCustomerNotes)->deleteNote($id);
        Log::info('Customer Note ID '.$id.' deleted by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
