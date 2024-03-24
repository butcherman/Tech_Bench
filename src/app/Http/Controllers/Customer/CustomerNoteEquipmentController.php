<?php

namespace App\Http\Controllers\Customer;

use App\Actions\BuildCustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerNoteEquipmentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Customer $customer, CustomerEquipment $equipment)
    {
        $this->authorize('create', CustomerNote::class);

        return Inertia::render('Customer/Note/Create', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'equipment' => $equipment,
            'siteList' => fn() => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn() => $customer->load('CustomerEquipment')->CustomerEquipment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerNoteRequest $request, Customer $customer, CustomerEquipment $equipment)
    {
        $newNote = $request->createNote();

        Log::channel('cust')->info('New Customer Note created for ' . $customer->name .
            ' by ' . $request->user()->username, $newNote->toArray());

        return redirect(route('customers.equipment.show', [$customer->slug, $equipment->cust_equip_id]))
            ->with('success', __('cust.note.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Customer $customer, CustomerEquipment $equipment, CustomerNote $note)
    {
        return Inertia::render('Customer/Note/Show', [
            'permissions' => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'siteList' => fn() => $note->CustomerSite->makeVisible('href'),
            'note' => fn() => $note,
            'equipment' => $equipment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Customer $customer, CustomerEquipment $equipment, CustomerNote $note)
    {
        $this->authorize('update', $note);

        return Inertia::render('Customer/Note/Edit', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'siteList' => fn() => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn() => $customer->CustomerEquipment,
            'note' => fn() => $note->load('CustomerSite'),
            'equipment' => $equipment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerNoteRequest $request, Customer $customer, CustomerEquipment $equipment, CustomerNote $note)
    {
        $updatedNote = $request->updateNote();

        Log::channel('cust')->info('Customer Note for ' . $customer->name .
            ' updated by ' . $request->user()->username, $updatedNote->toArray());

        return redirect(route('customers.equipment.notes.show', [
            $customer->slug,
            $equipment->cust_equip_id,
            $updatedNote->note_id
        ]))->with('success', __('cust.note.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Customer $customer, CustomerEquipment $equipment, CustomerNote $note)
    {
        $this->authorize('delete', $note);

        $note->delete();

        Log::channel('cust')->notice('Customer Note for ' . $customer->name .
            ' deleted by ' . $request->user()->username, $note->toArray());

        return redirect(route('customers.equipment.show', [$customer->slug, $equipment->cust_equip_id]))
            ->with('warning', __('cust.note.deleted'));
    }
}
