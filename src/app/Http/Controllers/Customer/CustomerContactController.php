<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerContactController extends Controller
{
    /**
     *
     */
    public function index()
    {
        //
        return 'index';
    }

    /**
     *
     */
    public function create()
    {
        //
        return 'create';
    }

    /**
     *
     */
    public function store(Request $request)
    {
        //
        return 'store';
    }

    /**
     *
     */
    public function show(string $id)
    {
        //
        return 'show';
    }

    /**
     *
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     *
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     *
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }

    /**
    *
    */
    public function restore(string $id)
    {
        //
        return 'restore';
    }

    /**
    *
    */
    public function forceDelete(string $id)
    {
        //
        return 'force delete';
    }
}
