<?php

namespace App\Http\Controllers\Equipment;

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\DataTypeRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentType;

class DataTypesController extends Controller
{
    /**
     * Display a listing of the Data Types
     */
    public function index()
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataTypes/Index', [
            'data-list' => DataFieldType::all(),
        ]);
    }

    /**
     * Store a newly created Data Type
     */
    public function store(DataTypeRequest $request)
    {
        DataFieldType::create($request->only('name'));

        Log::info('New Customer Data Type '.$request->name.' has been created by '.$request->user()->username);
        return back()->with('success', __('equip.data_type.created'));
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
     * Update the specified Data Type
     */
    public function update(DataTypeRequest $request, DataFieldType $data_type)
    {
        $data_type->update($request->only('name'));
        Log::info('Customer Data Type '.$request->name.' has been updated by '.$request->user()->username);

        return back()->with('success', __('equip.data_type.updated'));
    }

    /**
     * Remove a Data Field Type from DB
     */
    public function destroy(DataFieldType $data_type)
    {
        $this->authorize('viewAny', EquipmentType::class);
        $data_type->delete();

        Log::notice('Customer Data Type '.$data_type->name.' has been deleted by '.Auth::user()->username);
        return back()->with('success', __('equip.data_type.destroyed'));
    }
}
