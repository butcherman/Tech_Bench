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
     * Display a listing equipment with a link to create or edit workbook data.
     */
    public function index(): Response
    {
        $this->authorize('manage', EquipmentWorkbook::class);

        return Inertia::render('Equipment/Workbook/Index', [
            'equipment-list' => CacheData::equipmentCategories(),
        ]);
    }

    /**
     * Show the workbook canvas to create/edit workbook
     */
    public function create(EquipmentType $equipment_type): Response
    {
        $this->authorize('manage', EquipmentWorkbook::class);

        $workbook = $this->svc->getWorkbook($equipment_type, true);

        // Create session data for the live preview
        session()->put('workbookData-'.$equipment_type->equip_id, $workbook);

        return Inertia::render('Equipment/Workbook/Create', [
            'equipment-type' => $equipment_type,
            'workbook-data' => $workbook,
        ]);
    }

    /**
     * Save the workbook template in the database.
     */
    public function store(EquipmentWorkbookRequest $request, EquipmentType $equipment_type): JsonResponse
    {
        $this->svc->updateWorkbookBuilder($request->safe()->collect(), $equipment_type);

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentType $equipment_type): Response
    {
        $this->authorize('manage', EquipmentWorkbook::class);

        return Inertia::render('Equipment/Workbook/Show', [
            'equipment-type' => $equipment_type,
        ]);
    }

    /**
     * Pull unsaved changes from the session to send to preview mode
     */
    public function edit(EquipmentWorkbook $equipment_type): JsonResponse
    {
        $this->authorize('manage', EquipmentWorkbook::class);

        return response()
            ->json(session()->get('workbookData-'.$equipment_type->equip_id));
    }

    /**
     * Put unsaved changes into the session to be sent to preview mode
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
