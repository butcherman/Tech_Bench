<?php

namespace App\Domains\Equipment;

use App\SystemCategories;

use Illuminate\Support\Facades\Log;

class GetCategory
{
    //  Pull all equipment categories
    public function getCategoryData($catID)
    {
        return SystemCategories::findOrFail($catID);
    }
}
