<?php

namespace App\Domains\TechTips;

use App\Http\Resources\TechTipTypesCollection;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use App\TechTipTypes;

class GetTechTipTypes
{


    public function execute($collection = false)
    {
        $types = TechTipTypes::all();
        Log::debug('Tech Tip Types gathered.  Data - ', array($types));
        if($collection)
        {
            return new TechTipTypesCollection($types);
        }

        return $types;
    }
}
