<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/*
|-------------------------------------------------------------------------------
| DB Exception Facade provides quick access to check database errors and
| throw custom exceptions as needed.
|-------------------------------------------------------------------------------
*/

class DbException extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'DbException';
    }
}
