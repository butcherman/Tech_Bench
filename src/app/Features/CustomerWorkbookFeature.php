<?php

namespace App\Features;

/*
|-------------------------------------------------------------------------------
| Customer Workbook Feature creates an onboarding workbook that can be used
| to gather initial customer information for the installation of equipment
|-------------------------------------------------------------------------------
*/

class CustomerWorkbookFeature
{
    public function resolve(): mixed
    {
        return config('customer.enable_workbooks');
    }
}
