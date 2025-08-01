<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/*
|-------------------------------------------------------------------------------
| Cache Facade provides quick access to Cache Helper which will define all
| data cache and create their default values if they do not exist this will
| prevent us from having to define default values every time we access the
| cache.
|--------------------------------------------------------------------------------
*/

class CacheData extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'CacheData';
    }
}
