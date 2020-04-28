<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Customers;
use App\CustomerNotes;

use App\Http\Requests\CustomerEditNoteRequest;
use App\Http\Requests\CustomerNewNoteRequest;

class SetCustomerNotes
{
    protected $custID;

    public function __construct($custID)
    {
        $this->custID = $custID;
    }

    //  Create a new note for the customer
    public function createNote(CustomerNewNoteRequest $request)
    {
        CustomerNotes::create([
            'cust_id'     => $request->cust_id,
            'user_id'     => Auth::user()->user_id,
            'shared'      => $request->shared,
            'urgent'      => $request->urgent,
            'subject'     => $request->subject,
            'description' => $request->description,
        ]);

        Log::info('New Customer Note Created for Customer ID - '.$this->custID.' by '.Auth::user()->full_name.'.  Note details - ', array($request));
        return true;
    }

    //  Update an existing note for the customer
    public function updateNote(CustomerEditNoteRequest $request, $noteID)
    {
        if($request->shared)
        {
            $this->checkParent();
        }

        CustomerNotes::find($noteID)->update([
            'cust_id'     => $request->cust_id,
            'shared'      => $request->shared,
            'urgent'      => $request->urgent,
            'subject'     => $request->subject,
            'description' => $request->description,
        ]);

        Log::info('Customer Note updated for Customer ID '.$this->custID.' updated by '.Auth::user()->full_name.'. Note Details - ', array($request));
        return true;
    }

    //  Delete an existing note for the customer
    public function deleteNote($noteID)
    {
        CustomerNotes::find($noteID)->delete();
        Log::notice('Customer Note ID - '.$noteID.' deleted by '.Auth::user()->full_name);
        return true;
    }

    //  Verify if the parent should get the note
    protected function checkParent()
    {
        $parent = Customers::find($this->custID)->parent_id;
        if($parent)
        {
            $this->custID = $parent;
        }
    }
}
