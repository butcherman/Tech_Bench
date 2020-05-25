<?php

namespace App\Domains\TechTips;

use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use App\TechTips;


class GetTechTipStats
{
    public function getTipsInLastDays($numDays = 30)
    {
        $tips = TechTips::where('created_at', '>', Carbon::now()->subDays($numDays))->count();
        Log::debug('Retrieved new Tech Tip count for last '.$numDays.' days.  Count - $tips');

        return $tips;
    }

    public function getTotalTipsCount()
    {
        $tipsTotal = TechTips::all()->count();
        Log::debug('Retrieved total Tech Tip count.  Count - '.$tipsTotal);

        return $tipsTotal;
    }
}
