<?php

namespace App\Http\Controllers\Customer;

use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerNoteController extends Controller
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
    public function create(Request $request, Customer $customer): Response
    {
        $this->authorize('create', CustomerNote::class);

        return Inertia::render('Customer/Note/Create', [
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'customer' => fn() => $customer,
            'siteList' => fn() => $customer->Sites->makeVisible(['href']),
            'equipmentList' => fn() => $customer->Equipment,
        ]);
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
