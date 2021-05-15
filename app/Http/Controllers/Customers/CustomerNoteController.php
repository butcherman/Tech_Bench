<?php

namespace App\Http\Controllers\Customers;

use App\Models\CustomerNote;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerNoteRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerNoteController extends Controller
{
    /**
     *  Store a new customer note
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

        CustomerNote::create([
            'cust_id'    => $cust_id,
            'created_by' => $request->user()->user_id,
            'urgent'     => $request->urgent,
            'shared'     => $request->shared,
            'subject'    => $request->subject,
            'details'    => $request->details,
        ]);

        Log::channel('cust')->info('New Customer Note created for Customer '.$cust->name.' by '.Auth::user()->username);
        return response()->noContent();
    }

    /**
     *  Get all of the notes for a customer
     */
    public function show($id)
    {
        $cust = Customer::findOrFail($id);

        return CustomerNote::where('cust_id', $id)
                ->when($cust->parent_id, function($q) use ($cust)
                {
                    $q->orWhere('cust_id', $cust->parent_id)->where('shared', true);
                })
                ->orderBy('urgent', 'DESC')
                ->get();
    }

    /**
     *  Modify an existing note
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

        CustomerNote::find($id)->update([
            'cust_id'    => $cust_id,
            'updated_by' => $request->user()->user_id,
            'urgent'     => $request->urgent,
            'shared'     => $request->shared,
            'subject'    => $request->subject,
            'details'    => $request->details,
        ]);

        Log::channel('cust')->info('Customer Note ID '.$id.' updated for Customer '.$cust->name.' by '.Auth::user()->username);
        return response()->noContent();
    }

    /**
     *  Delete a customer Note
     */
    public function destroy($id)
    {
        $this->authorize('delete', CustomerNote::class);

        CustomerNote::find($id)->delete();

        Log::channel('cust')->notice('Customer Note ID '.$id.' deleted by '.Auth::user()->username);
        return response()->noContent();
    }

    /*
    *   Restore a deleted note
    */
    public function restore($id)
    {
        $this->authorize('restore', CustomerNote::class);
        $note = CustomerNote::withTrashed()->where('note_id', $id)->first();
        $note->restore();

        Log::channel('cust')->info('Customer Note '.$note->note_id.' was restored for Customer ID '.$note->cust_id.' by '.Auth::user()->username);
        return redirect()->back()->with(['message' => 'Customer Note restored', 'type' => 'success']);
    }

    /*
    *   Permanently delete a note
    */
    public function forceDelete($id)
    {
        $this->authorize('forceDelete', CustomerNote::class);

        $note = CustomerNote::withTrashed()->where('note_id', $id)->first();

        Log::channel('cust')->alert('Customer Note '.$note->subject.' has been permanently deleted by '.Auth::user()->username);
        $note->forceDelete();

        return redirect()->back()->with(['message' => 'Note permanently deleted', 'type' => 'danger']);
    }
}
