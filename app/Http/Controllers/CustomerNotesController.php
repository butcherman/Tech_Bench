<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Customers;
use App\CustomerNotes;

class CustomerNotesController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Load the new note form
    public function create()
    {
        return view('customer.form.newNote');
    }

    //  Submit the new customer note
    public function store(Request $request)
    {
        $request->validate([
            'custID'      => 'required|numeric',
            'subject'     => 'required',
            'note'        => 'required'
        ]);
        
        $noteID = CustomerNotes::create([
            'cust_id'     => $request->custID,
            'user_id'     => Auth::user()->user_id,
            'urgent'      => isset($request->urgent) && $request->urgent ? true : false,
            'subject'     => $request->subject,
            'description' => $request->note
        ]);
        
        Log::info('Customer Note Created for Customer ID-'.$request->custID.' by User ID-'.Auth::user()->user_id.'.  New Note ID-'.$noteID->note_id);
    }

    //  Show all customer notes
    public function show($id)
    {
        $notes = CustomerNotes::where('cust_id', $id)->orderBy('urgent', 'desc')->get();
        
        return view('customer.notes', [
            'notes' => $notes
        ]);
    }

    //  Open the Edit Note form
    public function edit($id)
    {
        $note = CustomerNotes::find($id);
        
        return view('customer.form.editNote', [
            'noteID' => $id,
            'note'   => $note
        ]);
    }

    //  Update a customer note
    public function update(Request $request, $id)
    {
        $request->validate([
            'subject'     => 'required',
            'description' => 'required'
        ]);
        
        CustomerNotes::find($id)->update(
        [
            'urgent'      => isset($request->urgent) && $request->urgent ? true : false,
            'subject'     => $request->subject,
            'description' => $request->description
        ]);
        
        Log::info('Customer Note ID-'.$id.' updated by User ID-'.Auth::user()->user_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
