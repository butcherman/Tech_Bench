<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Log;

use App\CustomerFiles;

class getCustomerFiles extends GetCustomerDetails
{
    //  Get all files belonging to the customer (or its parent)
    public function execute($custID)
    {
        $files = $this->getAllFiles($custID);

        if($parent = $this->getParentID($custID))
        {
            $files = $files->merge($this->getAllFiles($parent, true));
        }

        Log::debug('Customer Files for Customer ID'.$custID.' gathered.  Data - ', $files->toArray());
        return $files;
    }

    //  Get the files for the specified customer id
    protected function getAllFiles($custID, $shared = false)
    {
        return CustomerFiles::where('cust_id', $custID)
            ->when($shared, function($q)
            {
                $q->where('shared', 1);
            })
            ->with('Files')
            ->with('CustomerFileTypes')
            ->with('User')
            ->get();
    }
}
