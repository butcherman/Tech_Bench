<?php

namespace App\Domains\Customers;

use App\CustomerNotes;

use Illuminate\Support\Facades\Log;

class GetCustomerNotes extends GetCustomerDetails
{
    //  Get all notes belonging to a customer or shared notes belonging to its parent
    public function execute($custID)
    {
        $notes = $this->getNotes($custID);

        if($parent = $this->getParentID($custID))
        {
            $notes = $notes->merge($this->getNotes($parent, true));
        }

        Log::debug('Customer Notes for Customer ID'.$custID.' gathered.  Data - ', $notes->toArray());
        return $notes;
    }

    //  Get the details of a single note
    public function getOneNote($noteID)
    {
        $note = CustomerNotes::findOrFail($noteID);
        Log::debug('Note ID '.$noteID.' pulled for Customer ID '.$note->cust_id.'.  Data - ', $note->toArray());
        return $note;
    }

    //  Get the notes for the specified customer
    protected function getNotes($custID, $shared = false)
    {
        return CustomerNotes::where('cust_id', $custID)
            ->when($shared, function($q)
            {
                $q->where('shared', 1);
            })
            ->orderBy('urgent', 'DESC')
            ->get();
    }
}
