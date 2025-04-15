<?php

namespace App\Http\Controllers\Customer;

use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    /**
     * Search page for finding a customer profile.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Customer/Index', [
            'permissions' => UserPermissions::customerPermissions($request->user()),
        ]);
    }

    /**
     * Create new customer profile form.
     */
    public function create(): Response
    {
        $this->authorize('create', Customer::class);

        return Inertia::render('Customer/Create', [
            'select-id' => fn() => config('customer.select_id'),
            'default-state' => fn() => config('customer.default_state'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return 'store';
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
