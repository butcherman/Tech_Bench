<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DbException extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'DbException';
    }
}
