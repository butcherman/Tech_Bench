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

class CustomerNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Customer $customer)
    {
        return Inertia::render('Customer/Note/Index', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'notes' => fn() => $customer->CustomerNote,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Customer $customer)
    {
        $this->authorize('create', CustomerNote::class);

        return Inertia::render('Customer/Note/Create', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'siteList' => fn() => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn() => $customer
                ->load('CustomerEquipment')
                ->CustomerEquipment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerNoteRequest $request, Customer $customer)
    {
        $newNote = $request->createNote();

        Log::channel('cust')->info('New Customer Note created for ' . $customer->name .
            ' by ' . $request->user()->username, $newNote->toArray());

        return redirect(route('customers.show', $customer->slug))
            ->with('success', __('cust.note.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Customer $customer, CustomerNote $note)
    {
        return Inertia::render('Customer/Note/Show', [
            'permissions' => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'siteList' => fn() => $note->CustomerSite->makeVisible('href'),
            'note' => fn() => $note,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Customer $customer, CustomerNote $note)
    {
        $this->authorize('update', $note);

        return Inertia::render('Customer/Note/Edit', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'siteList' => fn() => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn() => $customer->CustomerEquipment,
            'note' => fn() => $note->load('CustomerSite'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerNoteRequest $request, Customer $customer, CustomerNote $note)
    {
        $updatedNote = $request->updateNote();

        Log::channel('cust')->info('Customer Note for ' . $customer->name .
            ' updated by ' . $request->user()->username, $updatedNote->toArray());

        return redirect(route('customers.notes.show', [
            $customer->slug,
            $updatedNote->note_id
        ]))->with('success', __('cust.note.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Customer $customer, CustomerNote $note)
    {
        $this->authorize('delete', $note);

        $note->delete();

        Log::channel('cust')->notice('Customer Note for ' . $customer->name .
            ' deleted by ' . $request->user()->username, $note->toArray());

        return redirect(route('customers.show', $customer->slug))
            ->with('warning', __('cust.note.deleted'));
    }

    /**
     * Restore a soft deleted note
     */
    public function restore(Request $request, Customer $customer, CustomerNote $note)
    {
        $this->authorize('restore', $note);

        $note->restore();
        Log::channel('cust')
            ->info('Customer Note restored for ' . $customer->name . ' by ' .
                $request->user()->username, $note->toArray());

        return back()->with('success', __('cust.note.restored'));
    }

    /**
     * remove a soft deleted note
     */
    public function forceDelete(Request $request, Customer $customer, CustomerNote $note)
    {

        $this->authorize('force-delete', $note);

        $note->forceDelete();
        Log::channel('cust')
            ->notice('Customer NOte force deleted for ' . $customer->name .
                ' by ' . $request->user()->username, $note->toArray());

        return back()
            ->with('warning', __('cust.note.force_deleted', [
                'cont' => $note->name
            ]));
    }
}
