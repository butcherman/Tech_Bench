<?php

namespace App\Http\Controllers\Equipment;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentWorkbookRequest;
use App\Models\EquipmentType;
use App\Services\Equipment\EquipmentWorkbookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EquipmentWorkbookController extends Controller
{
    public function __construct(protected EquipmentWorkbookService $svc)
    {
    }

    /**
     * Show a list of equipment with link to edit workbook data
     */
    public function index()
    {
        $this->authorize('create', EquipmentType::class);

        return Inertia::render('Equipment/Workbook/Index', [
            'equipment-list' => CacheData::equipmentCategories(),
        ]);
    }

    /**
     * Show Workbook Builder
     */
    public function create(EquipmentType $equipment_type)
    {
        $this->authorize('update', $equipment_type);

        $workbook = $this->svc->getWorkbook($equipment_type, true);

        session()->put('workbookData-' . $equipment_type->equip_id, $workbook);

        return Inertia::render('Equipment/Workbook/Create', [
            'equipment-type' => $equipment_type,
            'workbook-data' => $workbook,
        ]);
    }

    /**
     *
     */
    public function store(EquipmentWorkbookRequest $request, EquipmentType $equipment_type): JsonResponse
    {
        $this->svc->updateWorkbookBuilder($request->safe()->collect(), $equipment_type);

        return response()->json(['success' => true]);
    }

    /**
     *
     */
    public function show(EquipmentType $equipment_type)
    {
        $this->authorize('update', $equipment_type);

        return Inertia::render('Equipment/Workbook/Show');
    }

    /**
     *
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     *
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     *
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }

    /**
     *
     */
    public function restore(string $id)
    {
        //
        return 'restore';
    }

    /**
     *
     */
    public function forceDelete(string $id)
    {
        //
        return 'force delete';
    }
}
