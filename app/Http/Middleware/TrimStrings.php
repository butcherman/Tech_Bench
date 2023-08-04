<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

/**
 * Clear whitespace from front and back of all inputs
 */
class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
