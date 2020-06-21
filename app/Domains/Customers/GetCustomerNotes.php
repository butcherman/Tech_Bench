<?php

namespace App\Domains\Customers;

use App\CustomerNotes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetCustomerNotes extends GetCustomerDetails
{
    public function execute($custID)
    {
        $notes = $this->getNotes($custID);

        if($parent = $this->getParentID($custID))
        {
            $notes = $notes->merge($this->getNotes($parent, true));
        }

        return $notes;
    }

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
