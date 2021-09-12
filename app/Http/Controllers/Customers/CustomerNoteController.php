<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerNoteRequest;

use App\Events\Customers\Notes\CustomerNoteAddedEvent;
use App\Events\Customers\Notes\CustomerNoteDeletedEvent;
use App\Events\Customers\Notes\CustomerNoteUpdatedEvent;
use App\Events\Customers\Notes\CustomerNoteRestoredEvent;
use App\Events\Customers\Notes\CustomerNoteForceDeletedEvent;

use App\Models\Customer;
use App\Models\CustomerNote;

class CustomerNoteController extends Controller
{
    /**
     * Store a new customer note
     */
    public function store(CustomerNoteRequest $request)
    {
        $cust    = Customer::findOrFail($request->cust_id);
        $cust_id = $cust->cust_id;

        //  If the equipment is shared, it must be assigned to the parent site
        if($request->shared && $cust->parent_id > 0)
        {
            $cust_id = $cust->parent_id;
        }

        $note = CustomerNote::create([
            'cust_id'    => $cust_id,
            'created_by' => $request->user()->user_id,
            'urgent'     => $request->urgent,
            'shared'     => $request->shared,
            'subject'    => $request->subject,
            'details'    => $request->details,
        ]);

        event(new CustomerNoteAddedEvent($cust, $note));
        return back()->with([
            'message' => 'Note Created',
            'type'    => 'success',
        ]);
    }

    /**
     * Update an existing customer note
     */
    public function update(CustomerNoteRequest $request, $id)
    {
        $cust    = Customer::findOrFail($request->cust_id);
        $cust_id = $cust->cust_id;

        //  If the equipment is shared, it must be assigned to the parent site
        if($request->shared && $cust->parent_id > 0)
        {
            $cust_id = $cust->parent_id;
        }

        $note = CustomerNote::find($id);
        $note->update([
            'cust_id'    => $cust_id,
            'updated_by' => $request->user()->user_id,
            'urgent'     => $request->urgent,
            'shared'     => $request->shared,
            'subject'    => $request->subject,
            'details'    => $request->details,
        ]);

        event(new CustomerNoteUpdatedEvent($cust, $note));
        return back()->with([
            'message' => 'Note Updated',
            'type'    => 'success',
        ]);
    }

    /**
     * Delete a customer note
     */
    public function destroy($id)
    {
        $this->authorize('delete', CustomerNote::class);
        $note = CustomerNote::find($id);
        $note->delete();

        event(new CustomerNoteDeletedEvent(Customer::find($note->cust_id), $note));
        return back()->with([
            'message' => 'Note Deleted',
            'type'    => 'danger',
        ]);
    }

    /*
    *   Restore a deleted note
    */
    public function restore($id)
    {
        $this->authorize('restore', CustomerNote::class);
        $note = CustomerNote::withTrashed()->where('note_id', $id)->first();
        $note->restore();

        event(new CustomerNoteRestoredEvent(Customer::find($note->cust_id), $note));
        return redirect()->back()->with(['message' => 'Customer Note restored', 'type' => 'success']);
    }

    /*
    *   Permanently delete a note
    */
    public function forceDelete($id)
    {
        $this->authorize('forceDelete', CustomerNote::class);

        $note = CustomerNote::withTrashed()->where('note_id', $id)->first();
        $note->forceDelete();

        event(new CustomerNoteForceDeletedEvent(Customer::find($note->cust_id), $note));
        return redirect()->back()->with(['message' => 'Note permanently deleted', 'type' => 'danger']);
    }
}
