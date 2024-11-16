<?php

namespace App\Http\Controllers\Customer;

use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerNote;
use App\Services\Customer\CustomerNoteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerNoteController extends Controller
{
    public function __construct(protected CustomerNoteService $svc) {}

    /**
     * Display a listing of the Customer Note.
     */
    public function index(Request $request, Customer $customer): Response
    {
        return Inertia::render('Customer/Note/Index', [
            'permissions' => fn () => UserPermissions::customerPermissions(
                $request->user()
            ),
            'customer' => fn () => $customer,
            'notes' => fn () => $customer->CustomerNote,
        ]);
    }

    /**
     * Show the form for creating a new Customer Note.
     */
    public function create(Request $request, Customer $customer): Response
    {
        $this->authorize('create', CustomerNote::class);

        return Inertia::render('Customer/Note/Create', [
            'permissions' => fn () => UserPermissions::customerPermissions(
                $request->user()
            ),
            'customer' => fn () => $customer,
            'siteList' => fn () => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn () => $customer
                ->load('CustomerEquipment')
                ->CustomerEquipment,
        ]);
    }

    /**
     * Store a newly created Customer Note in storage.
     */
    public function store(CustomerNoteRequest $request, Customer $customer): RedirectResponse
    {
        $this->svc->createCustomerNote(
            $request->safe()->collect(),
            $customer,
            $request->user()
        );

        return redirect(route('customers.show', $customer->slug))
            ->with('success', __('cust.note.created'));
    }

    /**
     * Display the specified Customer Note.
     */
    public function show(Request $request, Customer $customer, CustomerNote $note): Response
    {
        return Inertia::render('Customer/Note/Show', [
            'permissions' => fn () => UserPermissions::customerPermissions(
                $request->user()
            ),
            'customer' => fn () => $customer,
            'siteList' => fn () => $note->CustomerSite->makeVisible('href'),
            'note' => fn () => $note,
        ]);
    }

    /**
     * Show the form for editing the specified Customer Note.
     */
    public function edit(Request $request, Customer $customer, CustomerNote $note): Response
    {
        $this->authorize('update', $note);

        return Inertia::render('Customer/Note/Edit', [
            'permissions' => fn () => UserPermissions::customerPermissions(
                $request->user()
            ),
            'customer' => fn () => $customer,
            'siteList' => fn () => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn () => $customer->CustomerEquipment,
            'note' => fn () => $note->load('CustomerSite'),
        ]);
    }

    /**
     * Update the specified Customer Note in storage.
     */
    public function update(
        CustomerNoteRequest $request,
        Customer $customer,
        CustomerNote $note
    ): RedirectResponse {
        $updatedNote = $this->svc->updateCustomerNote(
            $request->safe()->collect(),
            $note,
            $request->user()
        );

        return redirect(route('customers.notes.show', [
            $customer->slug,
            $updatedNote->note_id,
        ]))->with('success', __('cust.note.updated'));
    }

    /**
     * Remove the specified Customer Note from storage.
     */
    public function destroy(Customer $customer, CustomerNote $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        $this->svc->destroyCustomerNote($note);

        return redirect(route('customers.show', $customer->slug))
            ->with('warning', __('cust.note.deleted'));
    }

    /**
     * Restore a soft deleted note
     */
    public function restore(Customer $customer, CustomerNote $note): RedirectResponse
    {
        $this->authorize('restore', $note);

        $this->svc->restoreCustomerNote($note);

        return back()->with('success', __('cust.note.restored'));
    }

    /**
     * remove a soft deleted note
     */
    public function forceDelete(Customer $customer, CustomerNote $note): RedirectResponse
    {
        $this->authorize('force-delete', $note);

        $this->svc->destroyCustomerNote($note, true);

        return back()
            ->with('warning', __('cust.note.force_deleted', [
                'cont' => $note->name,
            ]));
    }
}
