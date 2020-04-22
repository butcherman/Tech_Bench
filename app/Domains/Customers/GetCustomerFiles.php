<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;

use App\Customers;
use App\CustomerFiles;

class GetCustomerFiles
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
        $files = $this->getLocalFiles();

        if($hasParent)
        {
            $parentFiles = $this->getParentFiles($hasParent);
            return $files->merge($parentFiles);
        }
        return $files;
    }

    //  Retrieve any notes attached to the customer
    protected function getLocalFiles()
    {
        $files = CustomerFiles::where('cust_id', $this->custID)
                    ->with('Files')
                    ->with('CustomerFileTypes')
                    ->with('User')
                    ->get();

        Log::debug('Customer Notes Query completed for customer ID '.$this->custID.'.  Results - ', array($files));
        return $files;
    }

    //  Retrieve any notes attached to the parent customer
    protected function getParentFiles($parentID)
    {
        $parentFiles = Customerfiles::where('cust_id', $parentID)
                            ->where('shared', 1)
                            ->with('Files')
                            ->with('CustomerFileTypes')
                            ->with('User')
                            ->get();

        Log::debug('Customer Parent Notes Query completed for Customer ID '.$this->custID.'.  Results - ', array($parentFiles));
        return $parentFiles;
    }
}
