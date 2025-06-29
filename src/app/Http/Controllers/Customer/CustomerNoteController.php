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
     * Show a listing of notes belonging to the customer.
     */
    public function index(Request $request, Customer $customer): Response
    {
        return Inertia::render('Customer/Note/Index', [
            'permissions' => fn () => UserPermissions::customerPermissions($request->user()),
            'customer' => fn () => $customer,
            'noteList' => Inertia::defer(fn () => $customer->Notes),
        ]);
    }

    /**
     * Show form to create a new Customer Note.
     */
    public function create(Request $request, Customer $customer): Response
    {
        $this->authorize('create', CustomerNote::class);

        session()->flash('referer', $request->header('referer'));

        return Inertia::render('Customer/Note/Create', [
            'permissions' => fn () => UserPermissions::customerPermissions($request->user()),
            'customer' => fn () => $customer,
            'siteList' => fn () => $customer->Sites->makeVisible(['href']),
            'equipmentList' => fn () => $customer->Equipment->load('Sites'),
        ]);
    }

    /**
     * Save a new Customer Note.
     */
    public function store(CustomerNoteRequest $request, Customer $customer): RedirectResponse
    {
        $redirect = $request->session()->get('referer')
            ?? route('customers.show', $customer->slug);

        $this->svc->createCustomerNote(
            $request->safe()->collect(),
            $customer,
            $request->user()
        );

        return redirect($redirect)->with('success', __('cust.note.created'));
    }

    /**
     * Show the information for a specific Customer Note
     */
    public function show(Request $request, Customer $customer, CustomerNote $note): Response
    {
        session()->flash('referer', $request->header('referer'));

        return Inertia::render('Customer/Note/Show', [
            'permissions' => fn () => UserPermissions::customerPermissions($request->user()),
            'customer' => fn () => $customer,
            'note' => fn () => $note,
            'siteList' => fn () => $note->Sites->makeVisible('href'),
        ]);
    }

    /**
     * Show the form to edit a customer note.
     */
    public function edit(Request $request, Customer $customer, CustomerNote $note): Response
    {
        $this->authorize('update', $note);

        session()->flash('referer', $request->header('referer'));

        return Inertia::render('Customer/Note/Edit', [
            'permissions' => fn () => UserPermissions::customerPermissions($request->user()),
            'customer' => fn () => $customer,
            'note' => fn () => $note,
            'siteList' => fn () => $customer->Sites->makeVisible(['href']),
            'equipmentList' => fn () => $customer->Equipment->load('Sites'),
        ]);
    }

    /**
     * Update the details for an existing Customer Note.
     */
    public function update(CustomerNoteRequest $request, Customer $customer, CustomerNote $note): RedirectResponse
    {
        $redirect = $request->session()->get('referer')
            ?? route('customers.show', $customer->slug);

        $this->svc->updateCustomerNote($request->safe()->collect(), $note, $request->user());

        return redirect($redirect)->with('success', __('cust.note.updated'));
    }

    /**
     * Soft Delete a customer note.
     */
    public function destroy(Request $request, Customer $customer, CustomerNote $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        $redirect = $request->session()->get('referer')
            ?? route('customers.show', $customer->slug);

        $this->svc->destroyCustomerNote($note);

        return redirect($redirect)->with('warning', __('cust.note.deleted'));
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
     * Force Delete a soft deleted note
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
