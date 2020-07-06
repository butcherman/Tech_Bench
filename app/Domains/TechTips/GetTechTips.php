<?php

namespace App\Domains\TechTips;

use App\TechTips;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetTechTips
{
    public function getTip($id)
    {
        $tip = TechTips::where('tip_id', $id)->with('SystemTypes')->with('TechTipFiles.Files')->first();

        return $tip;
    }
}
