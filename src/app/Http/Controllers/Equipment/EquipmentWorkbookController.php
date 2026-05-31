<?php

namespace App\Http\Controllers\Equipment;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentWorkbookRequest;
use App\Models\EquipmentType;
use App\Models\EquipmentWorkbook;
use App\Services\Equipment\EquipmentWorkbookService;
use Illuminate\Http\Request;
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
    public function store(EquipmentWorkbookRequest $request, EquipmentType $equipment_type)
    {
        $this->svc->updateWorkbookBuilder($request->safe()->collect(), $equipment_type);

        return response()->json(['success' => true]);
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
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
