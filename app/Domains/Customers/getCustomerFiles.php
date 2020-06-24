<?php

namespace App\Domains\Customers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\CustomerFiles;


class getCustomerFiles extends GetCustomerDetails
{
    public function execute($custID)
    {
        $files = $this->getAllFiles($custID);

        if($parent = $this->getParentID($custID))
        {
            $files = $files->merge($this->getAllFiles($parent, true));
        }

        return $files;
    }

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
