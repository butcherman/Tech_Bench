<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TimezoneList extends Facade
{
    /*
    |---------------------------------------------------------------------------
    | Timezone List Facade quickly creates a list of Timezones that can be
    | applied to a select box in a form.
    |---------------------------------------------------------------------------
    */
    public static function getFacadeAccessor(): string
    {
        return 'TimezoneList';
    }
}
