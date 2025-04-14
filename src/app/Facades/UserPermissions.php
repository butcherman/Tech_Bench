<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/*
|-------------------------------------------------------------------------------
| User Permissions Facade allows quick access of user permissions for Tech
| Tips and Customers
|-------------------------------------------------------------------------------
*/

class UserPermissions extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'UserPermissions';
    }
}
