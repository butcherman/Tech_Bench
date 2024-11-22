<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserPermissions extends Facade
{
    /*
    |---------------------------------------------------------------------------
    | User Permissions Facade allows quick access of user permissions for Tech
    | Tips and Customers
    |---------------------------------------------------------------------------
    */
    public static function getFacadeAccessor(): string
    {
        return 'UserPermissions';
    }
}
