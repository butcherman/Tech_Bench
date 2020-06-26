<?php

namespace App\Domains\Customers;

use App\CustomerNotes;

use Illuminate\Support\Facades\Log;

class SetCustomerNotes extends GetCustomerDetails
{
    //  Attach a new note to the customer
    public function createNewNote($request, $custID, $userID)
    {
        if($request->shared)
        {
            $parent = $this->getParentID($custID);
            if($parent)
            {
                $custID = $parent;
            }
        }

        CustomerNotes::create([
            'cust_id'     => $custID,
            'user_id'     => $userID,
            'shared'      => $request->shared,
            'urgent'      => $request->urgent,
            'subject'     => $request->subject,
            'description' => $request->description,
        ]);

        return true;
    }

    //  Update an existing note
    public function updateNote($request, $custID, $noteID)
    {
        if($request->shared)
        {
            $parent = $this->getParentID($custID);
            if($parent)
            {
                $custID = $parent;
            }
        }

        CustomerNotes::find($noteID)->update([
            'cust_id'     => $custID,
            'shared'      => $request->shared,
            'urgent'      => $request->urgent,
            'subject'     => $request->subject,
            'description' => $request->description,
        ]);

        return true;
    }

    //  Remove a note from the customer
    public function deleteNote($noteID)
    {
        CustomerNotes::find($noteID)->delete();
        return true;
    }
}
