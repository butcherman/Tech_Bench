<?php

namespace App\Http\Controllers\Customer;

use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerEquipmentNoteController extends Controller
{
    /**
     * Show a listing of Equipment Notes and Customer General Notes.
     */
    public function index(Request $request, Customer $customer, CustomerEquipment $equipment)
    {
        return Inertia::render('Customer/Note/Index', [
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'customer' => fn() => $customer,
            'noteList' => $equipment->getNotes(),
            'equipment' => fn() => $equipment,
        ]);
    }

    /**
     * Show form to create a new Equipment Note.
     */
    public function create(Request $request, Customer $customer, CustomerEquipment $equipment): Response
    {
        $this->authorize('create', CustomerNote::class);

        session()->flash('referer', $request->header('referer'));

        return Inertia::render('Customer/Note/Create', [
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'customer' => fn() => $customer,
            'siteList' => fn() => $customer->Sites->makeVisible(['href']),
            'equipmentList' => fn() => [$equipment->load('Sites')],
            'activeEquipment' => fn() => $equipment,
        ]);
    }

    /**
     * Show a note belonging to a piece of equipment.
     */
    public function show(Request $request, Customer $customer, CustomerEquipment $equipment, CustomerNote $note)
    {
        return Inertia::render('Customer/Note/Show', [
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'customer' => fn() => $customer,
            'note' => fn() => $note,
            'siteList' => fn() => $note->Sites->makeVisible('href'),
            'equipment' => fn() => $equipment,
        ]);
    }

    /**
     *
     */
    public function edit(Request $request, Customer $customer, CustomerEquipment $equipment, CustomerNote $note)
    {
        $this->authorize('update', $note);

        session()->flash('referer', $request->header('referer'));

        return Inertia::render('Customer/Note/Edit', [
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'customer' => fn() => $customer,
            'note' => fn() => $note,
            'siteList' => fn() => $customer->Sites->makeVisible(['href']),
            'equipmentList' => fn() => [$equipment->load('Sites')],
            'equipment' => fn() => $equipment,
        ]);
    }
}
