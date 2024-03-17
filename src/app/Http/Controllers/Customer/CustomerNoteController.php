<?php

namespace App\Http\Controllers\Customer;

use App\Actions\BuildCustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerNoteController extends Controller
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
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Customer $customer, CustomerSite $site = null)
    {
        $this->authorize('create', CustomerNote::class);

        return Inertia::render('Customer/Note/Create', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'site' => fn() => $site,
            'siteList' => fn() => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn() => $site ? $site->load('SiteEquipment')->SiteEquipment
                : $customer->load('CustomerEquipment')->CustomerEquipment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerNoteRequest $request, Customer $customer, CustomerSite $site = null)
    {
        $newNote = $request->createNote();

        Log::channel('cust')->info('New Customer Note created for ' . $customer->name .
            ' by ' . $request->user()->username, $newNote->toArray());

        if ($site) {
            return redirect(route('customers.sites.show', [$customer->slug, $site->site_slug]))
                ->with('success', __('cust.note.created'));
        }

        return redirect(route('customers.show', $customer->slug))
            ->with('success', __('cust.note.created'));
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
