<?php

namespace App\Http\Controllers\Equipment;

use App\Events\Equipment\WorkbookCanvasEvent;
use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentWorkbookRequest;
use App\Models\EquipmentType;
use App\Services\Equipment\EquipmentWorkbookService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentWorkbookController extends Controller
{
    public function __construct(protected EquipmentWorkbookService $svc) {}

    /**
     * Show a list of equipment with link to edit workbook data
     */
    public function index(): Response
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Workbook/Index', [
            'equipment-list' => CacheData::equipmentCategories(),
        ]);
    }

    /**
     * Show Workbook Builder
     */
    public function create(EquipmentType $equipment_type): Response
    {
        $this->authorize('update', $equipment_type);

        $workbook = $this->svc->getWorkbook($equipment_type, true);

        // Update session data for the live preview
        session()->put('workbookData-'.$equipment_type->equip_id, $workbook);

        return Inertia::render('Equipment/Workbook/Create', [
            'equipment-type' => $equipment_type,
            'workbook-data' => $workbook,
        ]);
    }

    /**
     * Save workbook data to the database
     */
    public function store(EquipmentWorkbookRequest $request, EquipmentType $equipment_type): JsonResponse
    {
        $this->svc->updateWorkbookBuilder($request->safe()->collect(), $equipment_type);

        return response()->json(['success' => true]);
    }

    /**
     * Live Preview page for the workbook
     */
    public function show(EquipmentType $equipment_type): Response
    {
        $this->authorize('update', $equipment_type);

        return Inertia::render('Equipment/Workbook/Show', [
            'equipment-type' => $equipment_type,
        ]);
    }

    /**
     * Return the recent unsaved changes to the workbook.
     */
    public function edit(EquipmentType $equipment_type): mixed
    {
        $this->authorize('update', $equipment_type);

        return session()->get('workbookData-'.$equipment_type->equip_id);
    }

    /**
     * Store any recent unsaved changes to the workbook for the live preview
     */
    public function update(EquipmentWorkbookRequest $request, EquipmentType $equipment_type): JsonResponse
    {
        $request->session()->put(
            'workbookData-'.$equipment_type->equip_id,
            $request->safe()->collect()->get('workbook_data')
        );

        WorkbookCanvasEvent::dispatch($equipment_type);

        return response()->json(['success' => true]);
    }
}
