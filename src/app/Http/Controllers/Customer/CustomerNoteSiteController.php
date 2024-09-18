<?php

namespace App\Http\Controllers\Customer;

use App\Actions\BuildCustomerPermissions;
use App\Enum\CrudAction;
use App\Events\Customer\CustomerNoteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerNoteSiteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Customer $customer, CustomerSite $site): Response
    {
        $this->authorize('create', CustomerNote::class);

        return Inertia::render('Customer/Note/Create', [
            'permissions' => fn () => BuildCustomerPermissions::build($request->user()),
            'customer' => fn () => $customer,
            'site' => fn () => $site,
            'siteList' => fn () => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn () => $customer->load('CustomerEquipment')->CustomerEquipment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerNoteRequest $request, Customer $customer, CustomerSite $site): RedirectResponse
    {
        $newNote = $request->createNote();

        Log::info('New Customer Note created for '.$customer->name.
            ' by '.$request->user()->username, $newNote->toArray());

        event(new CustomerNoteEvent($customer, $newNote, CrudAction::Create));

        return redirect(route('customers.sites.show', [$customer->slug, $site->site_slug]))
            ->with('success', __('cust.note.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Customer $customer, CustomerSite $site, CustomerNote $note): Response
    {
        return Inertia::render('Customer/Note/Show', [
            'permissions' => fn () => BuildCustomerPermissions::build($request->user()),
            'customer' => fn () => $customer,
            'siteList' => fn () => $note->CustomerSite->makeVisible('href'),
            'note' => fn () => $note,
            'site' => fn () => $site,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Customer $customer, CustomerSite $site, CustomerNote $note): Response
    {
        $this->authorize('update', $note);

        return Inertia::render('Customer/Note/Edit', [
            'permissions' => fn () => BuildCustomerPermissions::build($request->user()),
            'customer' => fn () => $customer,
            'siteList' => fn () => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn () => $customer->CustomerEquipment,
            'note' => fn () => $note->load('CustomerSite'),
            'site' => fn () => $site,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        CustomerNoteRequest $request,
        Customer $customer,
        CustomerSite $site,
        CustomerNote $note
    ): RedirectResponse {
        $updatedNote = $request->updateNote();

        Log::info('Customer Note for '.$customer->name.
            ' updated by '.$request->user()->username, $updatedNote->toArray());

        event(new CustomerNoteEvent($customer, $updatedNote, CrudAction::Update));

        return redirect(route('customers.site.notes.show', [
            $customer->slug,
            $site->site_slug,
            $updatedNote->note_id,
        ]))->with('success', __('cust.note.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Customer $customer,
        CustomerSite $site,
        CustomerNote $note
    ): RedirectResponse {
        $this->authorize('delete', $note);

        $note->delete();

        Log::notice('Customer Note for '.$customer->name.
            ' deleted by '.$request->user()->username, $note->toArray());

        event(new CustomerNoteEvent($customer, $note, CrudAction::ForceDelete));

        return redirect(route('customers.sites.show', [
            $customer->slug,
            $site->site_slug,
        ]))->with('warning', __('cust.note.deleted'));
    }
}
