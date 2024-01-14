<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserPasswordController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        return Inertia::render('User/Password');
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        //
        return 'update';
    }
}
