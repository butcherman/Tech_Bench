<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Service\Cache;
use Illuminate\Http\Request;

class PhoneTypesController extends Controller
{
    public function index()
    {
    }

    /**
     * Show the form for creating the resource.
     */
    public function create()
    {
        return Cache::phoneTypes();
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): never
    {
        abort(404);
    }

    /**
     * Display the resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(): never
    {
        abort(404);
    }
}
