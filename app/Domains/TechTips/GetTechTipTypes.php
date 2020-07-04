<?php

namespace App\Domains\TechTips;

use App\TechTipTypes;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GetTechTipTypes
{
    public function execute()
    {
        return TechTipTypes::all();
    }
}
