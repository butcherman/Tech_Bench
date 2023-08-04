<?php

namespace App\Http\Controllers\Equipment;

use App\Exceptions\RecordInUseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\DataTypeRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * Data Types are specific information to be gathered for equipment that is assigned to a customer.
 * For example, most equipment software has a version, or IP Address, or unique login information
 */
class DataTypesController extends Controller
{
    /**
     * Display a listing of the existing Data Types
     */
    public function index()
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataTypes/Index', [
            'data-types' => DataFieldType::all(),
        ]);
    }

    /**
     * Show the form for creating a new data type
     */
    public function create()
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataTypes/Create');
    }

    /**
     * Store a newly created data type
     */
    public function store(DataTypeRequest $request)
    {
        $newField = DataFieldType::create($request->toArray());

        Log::info('New Data Field Type created by '.$request->user()->username, $newField->toArray());

        return redirect(route('data-types.index'))->with('success', __('equipment.data-field-type.created'));
    }

    /**
     * Show the form for editing the specified data type
     */
    public function edit(DataFieldType $data_type)
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataTypes/Edit', [
            'data-type' => $data_type,
        ]);
    }

    /**
     * Update the specified data type
     */
    public function update(DataTypeRequest $request, DataFieldType $data_type)
    {
        $data_type->update($request->toArray());

        Log::info('Data Type '.$data_type->name.' updated by '.$request->user()->username, $request->toArray());

        return redirect(route('data-types.index'))->with('success', __('equipment.data-field-type.updated'));
    }

    /**
     * Remove the specified data type
     * Note:  Data Type cannot be removed if it is in use
     */
    public function destroy(Request $request, DataFieldType $data_type)
    {
        $this->authorize('viewAny', EquipmentType::class);

        try {
            $data_type->delete();
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[1], [19, 1451])) {
                throw new RecordInUseException(__('equipment.data-field-type.in-use'), 0, $e);
            }

            // @codeCoverageIgnoreStart
            Log::error('Error when trying to delete Data Type '.$data_type->name, $e->errorInfo);

            return back()->withErrors(['error' => 'failed']);
            // @codeCoverageIgnoreEnd
        }

        Log::notice('Data Field '.$data_type->name.' deleted by '.$request->user()->username);

        return back()->with('warning', __('equipment.data-field-type.destroyed'));
    }
}
