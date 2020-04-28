<?php

namespace App\Domains\TechTips;

use Illuminate\Support\Facades\Log;

use App\TechTipTypes;

use App\Http\Resources\TechTipTypesCollection;

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
