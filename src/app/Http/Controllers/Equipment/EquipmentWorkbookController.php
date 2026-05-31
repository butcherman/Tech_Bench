<?php

namespace App\Http\Controllers\Equipment;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Models\EquipmentType;
use App\Models\EquipmentWorkbook;
use App\Services\Equipment\EquipmentWorkbookService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EquipmentWorkbookController extends Controller
{
    public function __construct(protected EquipmentWorkbookService $svc) {}

    /**
     * Display a listing equipment with a link to create or edit workbook data.
     */
    public function index()
    {
        $this->authorize('manage', EquipmentWorkbook::class);

        return Inertia::render('Equipment/Workbook/Index', [
            'equipment-list' => CacheData::equipmentCategories(),
        ]);
    }

    /**
     * Show the workbook canvas to create/edit workbook
     */
    public function create(EquipmentType $equipment_type)
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
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
