<?php

namespace App\Domains\Equipment;

use App\SystemCategories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetCategory
{
    public function getCategoryData($catID)
    {
        return SystemCategories::findOrFail($catID);
    }
}
