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
            'custID' => 'required|numeric',
            'title' => 'required',
            'note' => 'required'
        ]);
        
        $noteID = CustomerNotes::create([
            'cust_id' => $request->custID,
            'user_id' => Auth::user()->user_id,
            'urgent' => $request->urgent === 'urgent' ? true : false,
            'subject' => $request->title,
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
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Fetched Data - ', $notes->toArray());
        return response()->json($notes);
    }

    //  Update a customer note
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'note'  => 'required'
        ]);
        
        CustomerNotes::find($id)->update(
        [
            'urgent'      => $request->urgent === 'urgent' ? true : false,
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
        $note = CustomerNotes::find($id)->delete();
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('Customer Note ID-'.$id.' deleted by User ID-'.Auth::user()->user_id);
        
        return response()->json(['success' => true]);
    }
}
