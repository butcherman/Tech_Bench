<?php

namespace App\Domains\TechTips;

use Carbon\Carbon;

use App\TechTips;

class GetTechTipStats
{
    public function getTipsInLastDays($numDays = 30)
    {
        $tips  = TechTips::where('created_at', '>', Carbon::now()->subDays($numDays))->count();
        return $tips;
    }

    public function getTotalTechTipCount()
    {
        $tipsTotal   = TechTips::all()->count();
        return $tipsTotal;
    }
}
