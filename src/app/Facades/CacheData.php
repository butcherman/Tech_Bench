<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CacheData extends Facade
{
    /*
    |---------------------------------------------------------------------------
    | Cache Facade provides quick access to Cache Helper which will define all
    | data cache and create their default values if they do not exist this will
    | prevent us from having to define default values every time we access the
    | cache.
    |---------------------------------------------------------------------------
    */
    public static function getFacadeAccessor(): string
    {
        return 'CacheData';
    }
}
