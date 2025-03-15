<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DbException extends Facade
{
    /*
    |---------------------------------------------------------------------------
    | DB Exception Facade provides quick access to check database errors and
    | throw custom exceptions as needed.
    |---------------------------------------------------------------------------
    */
    public static function getFacadeAccessor(): string
    {
        return 'DbException';
    }
}
