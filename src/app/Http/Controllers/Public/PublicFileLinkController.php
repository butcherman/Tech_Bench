<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicFileLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return 'index';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return 'show';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }
}
