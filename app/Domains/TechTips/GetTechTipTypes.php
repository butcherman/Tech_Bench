<?php

namespace App\Domains\TechTips;

use App\TechTipTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetTechTipTypes
{
    public function execute()
    {
        return TechTipTypes::all();
    }
}
