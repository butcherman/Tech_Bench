<?php

namespace App\Http\Controllers\Equipment;

use App\Events\Equipment\EquipmentDataTypeCreatedEvent;
use App\Events\Equipment\EquipmentDataTypeDeletedEvent;
use App\Events\Equipment\EquipmentDataTypeUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\DataTypeRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DataTypesController extends Controller
{
    /**
     * Display a listing of all available Data Types
     */
    public function index()
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('DataTypes/Index', [
            'data_list' => DataFieldType::all(),
        ]);
    }

    /**
     * Create a new Data Type
     */
    public function store(DataTypeRequest $request)
    {
        $type = DataFieldType::create($request->only('name'));

        event(new EquipmentDataTypeCreatedEvent($type));
        return back()->with([
            'message' => 'Data Type has been created',
            'type'    => 'success',
        ]);
    }

    /**
     * Update the name of a Data Type
     */
    public function update(DataTypeRequest $request, $id)
    {
        $type = DataFieldType::find($id);
        $type->update($request->only('name'));

        event(new EquipmentDataTypeUpdatedEvent($type));
        return back()->with([
            'message' => 'Data Type has been updated',
            'type'    => 'success',
        ]);
    }

    /**
     * Remove the Data Field Type
     */
    public function destroy($id)
    {
        $type = DataFieldType::find($id);
        $this->authorize('create', EquipmentType::class);
        $type->delete();

        event(new EquipmentDataTypeDeletedEvent($type));
        return back()->with([
            'message' => 'Data Type has been deleted',
            'type'    => 'warning',
        ]);
    }
}
