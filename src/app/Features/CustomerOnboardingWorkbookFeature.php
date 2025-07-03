<?php

namespace App\Features;

use Illuminate\Support\Lottery;

class CustomerOnboardingWorkbookFeature
{
    /*
    |---------------------------------------------------------------------------
    | Onboarding Workbooks are custom forms created for each piece of equipiment
    | assigned to a customer. Workbook is used to collect information about that
    | customer and equipment to be used for installing the equipment.
    |---------------------------------------------------------------------------
    */
    public function resolve(): mixed
    {
        return config('customer.enable_workbooks');
    }
}
