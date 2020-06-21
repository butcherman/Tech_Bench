<?php

namespace App\Domains\Customers;

use App\CustomerNotes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class SetCustomerNotes extends GetCustomerDetails
{
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

    public function deleteNote($noteID)
    {
        CustomerNotes::find($noteID)->delete();
        return true;
    }
}
