<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\CustomerNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class CustomerNotesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Store a new customer note
    public function store(Request $request)
    {
        $request->validate([
            'cust_id' => 'required|numeric',
            'title'   => 'required',
            'note'    => 'required'
        ]);

        //  Determine if the note should go to the customer, or its parent
        $details = Customers::find($request->cust_id);
        if ($details->parent_id && $request->shared == 'true') {
            $request->cust_id = $details->parent_id;
        }

        $noteID = CustomerNotes::create([
            'cust_id'     => $request->cust_id,
            'user_id'     => Auth::user()->user_id,
            'urgent'      => $request->urgent,
            'shared'      => $request->shared == 'true' ? 1 : 0,
            'subject'     => $request->title,
            'description' => $request->note
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::info('New Customer Note Created for Customer ID-'.$request->custID.' by User ID-'.Auth::user()->user_id.'.  New Note ID-'.$noteID->note_id);
        return response()->json(['success' => true]);
    }

    //  Get the customer notes
    public function show($id)
    {
        $notes = CustomerNotes::where('cust_id', $id)->orderBy('urgent', 'desc')->get();

        //  Determine if there is a parent site with shared notes
        $parent = Customers::find($id)->parent_id;
        if ($parent) {
            $parentList = CustomerNotes::where('cust_id', $parent)->where('shared', 1)->orderBy('urgent', 'desc')->get();

            $notes = $notes->merge($parentList);
        }

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Fetched Data - ', $notes->toArray());
        return $notes;
    }

    //  Update a customer note
    public function update(Request $request, $id)
    {
        $request->validate([
            'cust_id' => 'required',
            'title'   => 'required',
            'note'    => 'required'
        ]);

        $details = Customers::find($request->cust_id);
        if ($details->parent_id && $request->shared == 'true') {
            $request->cust_id = $details->parent_id;
        }

        CustomerNotes::find($id)->update([
            'cust_id'     => $request->cust_id,
            'shared'      => $request->shared == 'true' ? 1 : 0,
            'urgent'      => $request->urgent,
            'subject'     => $request->title,
            'description' => $request->note
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::info('Customer Note ID-'.$id.' updated by User ID-'.Auth::user()->user_id);
        return response()->json(['success' => true]);
    }

    //  Delete a customer note
    public function destroy($id)
    {
        CustomerNotes::find($id)->delete();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('Customer Note ID-'.$id.' deleted by User ID-'.Auth::user()->user_id);
        return response()->json(['success' => true]);
    }
}
