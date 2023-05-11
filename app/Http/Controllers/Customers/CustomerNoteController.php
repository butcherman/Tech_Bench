<?php

namespace App\Http\Controllers\Customers;

use App\Actions\BuildCustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        CustomerNote::create($request->only(['cust_id', 'created_by', 'subject', 'details', 'shared', 'urgent']));

        return back()->with('success', 'New Note Created');
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

        return back()->with('success', 'Note Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerNote $note)
    {
        $this->authorize('delete', $note);
        $note->delete();

        return redirect(route('customers.show', $note->Customer->slug))->with('danger', 'Note Deleted');
    }

    /**
     * Restore a soft deleted note
     */
    public function restore(CustomerNote $note)
    {
        $this->authorize('restore', $note);

        $note->restore();
        return back()->with('success', 'Note Restored');
    }

    /**
     * Force Delete a soft deleted note
     */
    public function forceDelete(CustomerNote $note)
    {
        $this->authorize('force-delete', $note);
        $note->forceDelete();

        return back()->with('danger', 'Note Deleted');
    }
}
