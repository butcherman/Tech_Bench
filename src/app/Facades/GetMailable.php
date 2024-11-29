<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GetMailable extends Facade
{
    /*
    |---------------------------------------------------------------------------
    | Get Mailable Facade provides quick access to users that have their user
    | settings set to allow email notifications.
    |---------------------------------------------------------------------------
    */
    public static function getFacadeAccessor(): string
    {
        return 'GetMailable';
    }
}
