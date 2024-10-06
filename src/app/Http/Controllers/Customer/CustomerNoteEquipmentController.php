<?php

// TODO - Refactor

namespace App\Http\Controllers\Customer;

use App\Actions\CustomerPermissions;
use App\Enum\CrudAction;
use App\Events\Customer\CustomerNoteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerNoteEquipmentController extends Controller
{
    public function __construct(protected CustomerPermissions $permissions) {}

    /**
     * Show the form for creating a new Equipment Note.
     */
    public function create(Request $request, Customer $customer, CustomerEquipment $equipment): Response
    {
        $this->authorize('create', CustomerNote::class);

        return Inertia::render('Customer/Note/Create', [
            'permissions' => fn () => $this->permissions->get($request->user()),
            'customer' => fn () => $customer,
            'equipment' => fn () => $equipment,
            'siteList' => fn () => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn () => $customer->load('CustomerEquipment')->CustomerEquipment,
        ]);
    }

    /**
     * Store a newly created Equipment Note in storage.
     */
    public function store(
        CustomerNoteRequest $request,
        Customer $customer,
        CustomerEquipment $equipment
    ): RedirectResponse {
        $newNote = $request->createNote();

        Log::info('New Customer Note created for '.$customer->name.
            ' by '.$request->user()->username, $newNote->toArray());

        event(new CustomerNoteEvent($customer, $newNote, CrudAction::Create));

        return redirect(route('customers.equipment.show', [
            $customer->slug,
            $equipment->cust_equip_id,
        ]))->with('success', __('cust.note.created'));
    }

    /**
     * Display the specified Equipment Note.
     */
    public function show(
        Request $request,
        Customer $customer,
        CustomerEquipment $equipment,
        CustomerNote $note
    ): Response {
        return Inertia::render('Customer/Note/Show', [
            'permissions' => fn () => $this->permissions->get($request->user()),
            'customer' => fn () => $customer,
            'siteList' => fn () => $note->CustomerSite->makeVisible('href'),
            'note' => fn () => $note,
            'equipment' => fn () => $equipment,
        ]);
    }

    /**
     * Show the form for editing the specified Equipment Note.
     */
    public function edit(
        Request $request,
        Customer $customer,
        CustomerEquipment $equipment,
        CustomerNote $note
    ): Response {
        $this->authorize('update', $note);

        return Inertia::render('Customer/Note/Edit', [
            'permissions' => fn () => $this->permissions->get($request->user()),
            'customer' => fn () => $customer,
            'siteList' => fn () => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn () => $customer->CustomerEquipment,
            'note' => fn () => $note->load('CustomerSite'),
            'equipment' => $equipment,
        ]);
    }

    /**
     * Update the specified Equipment Note in storage.
     */
    public function update(
        CustomerNoteRequest $request,
        Customer $customer,
        CustomerEquipment $equipment,
        CustomerNote $note
    ): RedirectResponse {
        $updatedNote = $request->updateNote();

        Log::info('Customer Note for '.$customer->name.
            ' updated by '.$request->user()->username, $updatedNote->toArray());

        event(new CustomerNoteEvent($customer, $updatedNote, CrudAction::Update));

        return redirect(route('customers.equipment.notes.show', [
            $customer->slug,
            $equipment->cust_equip_id,
            $updatedNote->note_id,
        ]))->with('success', __('cust.note.updated'));
    }

    /**
     * Remove the specified Equipment Note from storage.
     */
    public function destroy(
        Request $request,
        Customer $customer,
        CustomerEquipment $equipment,
        CustomerNote $note
    ): RedirectResponse {
        $this->authorize('delete', $note);

        $note->delete();

        Log::notice('Customer Note for '.$customer->name.
            ' deleted by '.$request->user()->username, $note->toArray());

        event(new CustomerNoteEvent($customer, $note, CrudAction::ForceDelete));

        return redirect(route('customers.equipment.show', [
            $customer->slug,
            $equipment->cust_equip_id,
        ]))->with('warning', __('cust.note.deleted'));
    }
}
