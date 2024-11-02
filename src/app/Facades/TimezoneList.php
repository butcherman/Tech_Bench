<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TimezoneList extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'TimezoneList';
    }
}
