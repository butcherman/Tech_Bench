<?php

namespace App\Http\Controllers\Customer;

use App\Actions\BuildCustomerPermissions;
use App\Enum\CrudAction;
use App\Events\Customer\CustomerNoteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerNoteController extends Controller
{
    /**
     * Display a listing of the Customer Note.
     */
    public function index(Request $request, Customer $customer): Response
    {
        return Inertia::render('Customer/Note/Index', [
            'permissions' => fn () => BuildCustomerPermissions::build($request->user()),
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
            'permissions' => fn () => BuildCustomerPermissions::build($request->user()),
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
        $newNote = $request->createNote();

        Log::channel('cust')->info('New Customer Note created for '.$customer->name.
            ' by '.$request->user()->username, $newNote->toArray());

        event(new CustomerNoteEvent($customer, $newNote, CrudAction::Create));

        return redirect(route('customers.show', $customer->slug))
            ->with('success', __('cust.note.created'));
    }

    /**
     * Display the specified Customer Note.
     */
    public function show(Request $request, Customer $customer, CustomerNote $note): Response
    {
        return Inertia::render('Customer/Note/Show', [
            'permissions' => fn () => BuildCustomerPermissions::build($request->user()),
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
            'permissions' => fn () => BuildCustomerPermissions::build($request->user()),
            'customer' => fn () => $customer,
            'siteList' => fn () => $customer->CustomerSite->makeVisible('href'),
            'equipmentList' => fn () => $customer->CustomerEquipment,
            'note' => fn () => $note->load('CustomerSite'),
        ]);
    }

    /**
     * Update the specified Customer Note in storage.
     */
    public function update(CustomerNoteRequest $request, Customer $customer, CustomerNote $note): RedirectResponse
    {
        $updatedNote = $request->updateNote();

        Log::channel('cust')->info('Customer Note for '.$customer->name.
            ' updated by '.$request->user()->username, $updatedNote->toArray());

        event(new CustomerNoteEvent($customer, $updatedNote, CrudAction::Update));

        return redirect(route('customers.notes.show', [
            $customer->slug,
            $updatedNote->note_id,
        ]))->with('success', __('cust.note.updated'));
    }

    /**
     * Remove the specified Customer Note from storage.
     */
    public function destroy(Request $request, Customer $customer, CustomerNote $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        $note->delete();

        Log::channel('cust')->notice('Customer Note for '.$customer->name.
            ' deleted by '.$request->user()->username, $note->toArray());

        event(new CustomerNoteEvent($customer, $note, CrudAction::Destroy));

        return redirect(route('customers.show', $customer->slug))
            ->with('warning', __('cust.note.deleted'));
    }

    /**
     * Restore a soft deleted note
     */
    public function restore(Request $request, Customer $customer, CustomerNote $note): RedirectResponse
    {
        $this->authorize('restore', $note);

        $note->restore();

        Log::channel('cust')
            ->info('Customer Note restored for '.$customer->name.' by '.
                $request->user()->username, $note->toArray());

        event(new CustomerNoteEvent($customer, $note, CrudAction::Restore));

        return back()->with('success', __('cust.note.restored'));
    }

    /**
     * remove a soft deleted note
     */
    public function forceDelete(Request $request, Customer $customer, CustomerNote $note): RedirectResponse
    {

        $this->authorize('force-delete', $note);

        $note->forceDelete();

        Log::channel('cust')
            ->notice('Customer NOte force deleted for '.$customer->name.
                ' by '.$request->user()->username, $note->toArray());

        event(new CustomerNoteEvent($customer, $note, CrudAction::ForceDelete));

        return back()
            ->with('warning', __('cust.note.force_deleted', [
                'cont' => $note->name,
            ]));
    }
}
