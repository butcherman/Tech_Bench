<?php

namespace App\Http\Controllers\Customers;

use App\Actions\BuildCustomerPermissions;
use App\Events\Customer\CustomerNoteCreatedEvent;
use App\Events\Customer\CustomerNoteUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerNoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerNoteRequest $request)
    {
        $request->checkForShared();
        $request->merge(['created_by' => $request->user()->user_id]);
        $newNote = CustomerNote::create($request->only(['cust_id', 'created_by', 'subject', 'details', 'shared', 'urgent']));
        event(new CustomerNoteCreatedEvent($newNote->Customer, $newNote));
        Log::channel(['daily', 'cust'])->info('Note for '.$newNote->Customer->name.' created by '.$request->user()->username, $newNote->toArray());

        return back()->with('success', __('cust.note.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer, CustomerNote $note)
    {
        return Inertia::render('Customers/Notes/Show', [
            'permissions' => (new BuildCustomerPermissions)->execute($customer, Auth::user()),
            'customer' => $customer,
            'note' => $note,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerNoteRequest $request, CustomerNote $note)
    {
        $request->checkForShared();
        $request->merge(['updated_by' => $request->user()->user_id]);
        $note->update($request->only(['cust_id', 'updated_by', 'subject', 'details', 'shared', 'urgent']));
        event(new CustomerNoteUpdatedEvent($note->Customer, $note));

        return back()->with('success', __('cust.note.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerNote $note)
    {
        $this->authorize('delete', $note);
        $note->delete();

        return redirect(route('customers.show', $note->Customer->slug))->with('danger', __('cust.note.deleted'));
    }

    /**
     * Restore a soft deleted note
     */
    public function restore(CustomerNote $note)
    {
        $this->authorize('restore', $note);

        $note->restore();

        return back()->with('success', __('cust.note.restored'));
    }

    /**
     * Force Delete a soft deleted note
     */
    public function forceDelete(CustomerNote $note)
    {
        $this->authorize('force-delete', $note);
        $note->forceDelete();

        return back()->with('danger', __('cust.note.force_deleted'));
    }
}
