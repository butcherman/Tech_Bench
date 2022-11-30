<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CustomerEquipmentRequest;
use App\Models\CustomerEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified Customer Equipment Data
     */
    public function update(CustomerEquipmentRequest $request, CustomerEquipment $equipment)
    {
        $request->updateEquipData($equipment);

        return back()->with('success', 'done');
    }

    /**
     * Remove the specified Customer Equipment
     */
    public function destroy(CustomerEquipment $equipment)
    {
        $this->authorize('delete', $equipment);

        $equipment->delete();
        Log::stack(['daily', 'cust'])->info('Equipment '.$equipment->name.' deleted for Customer ID '.$equipment->cust_id.' by '.Auth::user()->username);
        return back()->with('success', 'deleted');
    }
}
