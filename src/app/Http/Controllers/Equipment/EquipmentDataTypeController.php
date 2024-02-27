<?php

namespace App\Http\Controllers\Equipment;

use App\Exceptions\Database\GeneralQueryException;
use App\Exceptions\Database\RecordInUseException;
use Illuminate\Database\QueryException;
use App\Service\Cache;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Http\Controllers\Controller;
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
        //
        return 'create';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return 'store';
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
        return 'edit';
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
    public function destroy(Request $request, DataFieldType $equipment_datum)
    {
        $this->authorize('viewAny', EquipmentType::class);

        // return $data_type;
        // dd($equipment_datum);

        try {
            $equipment_datum->delete();
            Cache::clearCache(['equipmentTypes', 'equipmentCategories']);
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[1], [19, 1451])) {
                throw new RecordInUseException(
                    $equipment_datum->name . ' is still in use and cannot be deleted',
                    0,
                    $e
                );
            } else {
                throw new GeneralQueryException('', 0, $e);
            }
        }

        Log::notice('Data Field ' . $equipment_datum->name . ' deleted by ' .
            $request->user()->username);

        return back()->with('warning', __('equipment.data-field-type.destroyed'));
    }
}
