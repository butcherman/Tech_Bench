<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;

use App\Customers;
use App\CustomerNotes;

class GetCustomerNotes
{
    protected $custID;

    public function __construct($custID)
    {
        $this->custID = $custID;
    }

    //  Get all notes that are assigned to the customer
    public function execute()
    {
        $hasParent = Customers::findOrFail($this->custID)->parent_id;
        $notes = $this->getLocalNotes();

        if($hasParent)
        {
            $parentNotes = $this->getParentNotes($hasParent);
            return $notes->merge($parentNotes);
        }
        return $notes;
    }

    //  Retrieve any notes attached to the customer
    protected function getLocalNotes()
    {
        $notes = CustomerNotes::where('cust_id', $this->custID)->orderBy('urgent', 'desc')->get();

        Log::debug('Customer Notes Query completed for customer ID '.$this->custID.'.  Results - ', array($notes));
        return $notes;
    }

    //  Retrieve any notes attached to the parent customer
    protected function getParentNotes($parentID)
    {
        $parentNotes = CustomerNotes::where('cust_id', $parentID)->where('shared', 1)->orderBy('urgent', 'desc')->get();

        Log::debug('Customer Parent Notes Query completed for Customer ID '.$this->custID.'.  Results - ', array($parentNotes));
        return $parentNotes;
    }
}
