<?php

namespace App\Domains\TechTips;

use App\TechTipComments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetTipComments
{
    public function execute($tipID)
    {
        $comments = TechTipComments::where('tip_id', $tipID)->with('User')->get();
        return $comments;
    }
}
