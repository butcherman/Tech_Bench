<?php

namespace App\Observers;

use App\Models\CustomerWorkbook;
use Illuminate\Support\Facades\Log;

class CustomerWorkbookObserver extends Observer
{
    /**
     * Handle the CustomerWorkbook "created" event.
     */
    public function created(CustomerWorkbook $customerWorkbook): void
    {
        Log::info(
            'Customer Workbook created for ' .
            $customerWorkbook->Customer->name . ' by ' . $this->user,
            [
                'Customer ID' => $customerWorkbook->Customer->cust_id,
                // 'Customer Equipment ID' =>  //
                // 'Customer Equipment Type' => //
                // 'Workbook Version' => //
            ]
        );
    }

    /**
     * Handle the CustomerWorkbook "updated" event.
     */
    public function updated(CustomerWorkbook $customerWorkbook): void
    {
        //
    }

    /**
     * Handle the CustomerWorkbook "deleted" event.
     */
    public function deleted(CustomerWorkbook $customerWorkbook): void
    {
        //
    }
}
