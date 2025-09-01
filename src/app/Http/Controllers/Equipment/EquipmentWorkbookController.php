<?php

namespace App\Http\Controllers\Equipment;

use App\Events\Equipment\WorkbookCanvasEvent;
use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentWorkbookRequest;
use App\Models\EquipmentType;
use App\Models\EquipmentWorkbook;
use App\Services\Equipment\EquipmentWorkbookService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentWorkbookController extends Controller
{
    public function __construct(protected EquipmentWorkbookService $svc) {}

    /**
     * Show a list of Equipment with link to create or edit workbook data.
     */
    public function index(): Response
    {
        $this->authorize('create', EquipmentWorkbook::class);

        return Inertia::render('Equipment/Workbook/Index', [
            'equipment-list' => CacheData::equipmentCategories(),
        ]);
    }

    /**
     * Show the Workbook Canvas.
     */
    public function create(EquipmentType $equipment_type): Response
    {
        $this->authorize('create', EquipmentWorkbook::class);

        $workbook = $this->svc->getWorkbook($equipment_type, true);

        // Create session data for the live preview
        session()->put('workbookData-'.$equipment_type->equip_id, $workbook);

        return Inertia::render('Equipment/Workbook/Create', [
            'equipment-type' => $equipment_type,
            'workbook-data' => $workbook,
        ]);
    }

    /**
     * Save Workbook Data to the Database.
     */
    public function store(EquipmentWorkbookRequest $request, EquipmentType $equipment_type): JsonResponse
    {
        $this->svc->updateWorkbookBuilder($request->safe()->collect(), $equipment_type);

        return response()->json(['success' => true]);
    }

    /**
     * Show a live preview of the workbook being edited.
     */
    public function show(EquipmentType $equipment_type): Response
    {
        $this->authorize('create', EquipmentWorkbook::class);

        return Inertia::render('Equipment/Workbook/Show', [
            'equipment-type' => $equipment_type,
        ]);
    }

    /**
     * Get unsaved changes made to the Workbook for live update
     */
    public function edit(EquipmentType $equipment_type): mixed
    {
        $this->authorize('create', EquipmentWorkbook::class);

        return session()->get('workbookData-'.$equipment_type->equip_id);
    }

    /**
     * Put unsaved changes for the live update
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
