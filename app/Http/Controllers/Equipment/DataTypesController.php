<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\DataTypeRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DataTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataTypes/Index', [
            'data-types' => DataFieldType::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Equipment/DataTypes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataTypeRequest $request)
    {
        $newField = DataFieldType::create($request->toArray());

        Log::info('New Data Field Type created by '.$request->user()->username, $newField->toArray());

        return redirect(route('data-types.index'))->with('success', __('equipment.data-field-type.created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataFieldType $data_type)
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataTypes/Edit', [
            'data-type' => $data_type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DataTypeRequest $request, DataFieldType $data_type)
    {
        $data_type->update($request->toArray());

        Log::info('Data Type '.$data_type->name.' updated by '.$request->user()->username, $request->toArray());

        return redirect(route('data-types.index'))->with('success', __('equipment.data-field-type.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, DataFieldType $data_type)
    {
        $this->authorize('viewAny', EquipmentType::class);

        $data_type->delete();

        Log::notice('Data Field '.$data_type->name.' deleted by '.$request->user()->username);

        return back()->with('warning', __('equipment.data-field-type.destroyed'));
    }
}
