<?php

namespace App\Http\Controllers\Equipment;

use App\Events\Equipment\WorkbookCanvasEvent;
use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentWorkbookRequest;
use App\Models\Customer;
use App\Models\EquipmentType;
use App\Models\EquipmentWorkbook;
use App\Services\Equipment\EquipmentWorkbookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentWorkbookController extends Controller
{
    /**
     * Show a list of all equipment types and links to create/edit workbook.
     */
    public function index(): Response
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/Workbook/Index', [
            'equipment-list' => CacheData::equipmentCategories(),
        ]);
    }

    /**
     * Show the Workbook Editor to create a new workbook
     */
    public function create(EquipmentWorkbookService $svc, EquipmentType $equipment_type): Response
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/Workbook/Create', [
            'equipment-type' => $equipment_type,
            'workbook-data' => $svc->getWorkbook($equipment_type, true),
        ]);
    }

    /**
     * Save the workbook
     */
    public function store(
        EquipmentWorkbookRequest $request,
        EquipmentWorkbookService $svc,
        EquipmentType $equipment_type
    ): JsonResponse {
        $svc->updateWorkbookBuilder($request->safe()->collect(), $equipment_type);

        return response()->json(['success' => true]);
    }

    /**
     *
     */
    public function show(EquipmentType $equipment_type): Response
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/Workbook/Show', [
            'equipment-type' => $equipment_type,
            'workbook-data' => json_decode(EquipmentWorkbook::find($equipment_type->equip_id)->workbook_data),
            'customer' => Customer::factory()->make(),
        ]);
    }

    /**
     *
     */
    public function edit(EquipmentType $equipment_type): Response
    {
        $this->authorize('viewAny', EquipmentType::class);

        return Inertia::render('Equipment/Workbook/Edit', [
            'equipment-type' => $equipment_type,
            'workbook-data' => json_decode(EquipmentWorkbook::find($equipment_type->equip_id)->workbook_data),
        ]);
    }

    /**
     * Save the workbook
     */
    public function update(EquipmentWorkbookRequest $request, EquipmentType $equipment_type): JsonResponse
    {
        WorkbookCanvasEvent::dispatch($equipment_type);

        return response()->json(['success' => true]);
    }

    /**
     *
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
