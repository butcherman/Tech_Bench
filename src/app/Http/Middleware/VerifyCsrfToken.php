<?php

// TODO - Refactor

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification
     */
    protected $except = [
        'upload-image',
        'upload-image/*',
    ];
}