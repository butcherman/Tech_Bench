<?php

// TODO - Refactor

namespace App\Http\Controllers\Equipment;

use App\Exceptions\Database\GeneralQueryException;
use App\Exceptions\Database\RecordInUseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\DataTypeRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Service\Cache;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EquipmentDataTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataType/Index', [
            'data-types' => DataFieldType::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataType/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataTypeRequest $request)
    {
        $newField = DataFieldType::create($request->toArray());

        Log::info('New Equipment Data Field created by '.
            $request->user()->username, $newField->toArray());

        return redirect(route('equipment-data.index'))
            ->with('success', __('equipment.data-field-type.created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataFieldType $equipment_datum)
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataType/Edit', [
            'data-field-type' => $equipment_datum,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DataTypeRequest $request, DataFieldType $equipment_datum)
    {
        $equipment_datum->update($request->toArray());

        Log::info('Equipment Data Type '.$equipment_datum->name.' updated by '.
            $request->user()->username, $equipment_datum->toArray());

        return redirect(route('equipment-data.index'))
            ->with('success', __('equipment.data-field-type.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, DataFieldType $equipment_datum)
    {
        $this->authorize('viewAny', EquipmentType::class);

        try {
            $equipment_datum->delete();
            Cache::clearCache(['equipmentTypes', 'equipmentCategories']);
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[1], [19, 1451])) {
                throw new RecordInUseException(
                    $equipment_datum->name.' is still in use and cannot be deleted',
                    0,
                    $e
                );
            } else {
                // @codeCoverageIgnoreStart
                throw new GeneralQueryException('', 0, $e);
                // @codeCoverageIgnoreEnd
            }
        }

        Log::notice('Data Field '.$equipment_datum->name.' deleted by '.
            $request->user()->username);

        return back()->with('warning', __('equipment.data-field-type.destroyed'));
    }
}
