<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerNoteRequest;
use App\Models\CustomerNote;
use Illuminate\Http\Request;

class CustomerNoteController extends Controller
{
    /**
     *  Redirecting back to customer page will refresh the notes list
     */
    public function index()
    {
        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerNoteRequest $request)
    {
        //  FIXME - if the note is shared, it is assigned to the parent

        $request->merge(['created_by' => $request->user()->user_id]);
        CustomerNote::create($request->only(['cust_id', 'created_by', 'subject', 'details', 'shared', 'urgent']));

        return back()->with('success', 'New Note Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }

    /**
     * Restore a soft deleted note
     */
    public function restore(CustomerNote $note)
    {
        //
        return 'restore';
    }

    /**
     * Force Delete a soft deleted note
     */
    public function forceDelete(CustomerNote $note)
    {
        //
        return 'force delete';
    }
}
