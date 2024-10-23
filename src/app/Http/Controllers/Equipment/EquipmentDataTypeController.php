<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\DataTypeRequest;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use App\Service\Equipment\EquipmentDataService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentDataTypeController extends Controller
{
    public function __construct(protected EquipmentDataService $svc) {}

    /**
     * Display a listing all Equipment Data Fields.
     */
    public function index(): Response
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataType/Index', [
            'data-types' => DataFieldType::all(),
        ]);
    }

    /**
     * Show the form for creating a Equipment Data Field.
     */
    public function create(): Response
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataType/Create');
    }

    /**
     * Store a newly created Equipment Data Field.
     */
    public function store(DataTypeRequest $request): RedirectResponse
    {
        $this->svc->createDataType($request->collect());

        return redirect(route('equipment-data.index'))
            ->with('success', __('equipment.data-field-type.created'));
    }

    /**
     * Show the form for editing the Equipment Data Field.
     */
    public function edit(DataFieldType $equipment_datum): Response
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/DataType/Edit', [
            'data-field-type' => $equipment_datum,
        ]);
    }

    /**
     * Update the Equipment Data Field.
     */
    public function update(
        DataTypeRequest $request,
        DataFieldType $equipment_datum
    ): RedirectResponse {
        $this->svc->updateDataType($request->collect(), $equipment_datum);

        return redirect(route('equipment-data.index'))
            ->with('success', __('equipment.data-field-type.updated'));
    }

    /**
     * Remove the Equipment Data Field.
     */
    public function destroy(DataFieldType $equipment_datum): RedirectResponse
    {
        $this->authorize('viewAny', EquipmentType::class);

        $this->svc->destroyDataType($equipment_datum);

        return back()
            ->with('warning', __('equipment.data-field-type.destroyed'));
    }
}
