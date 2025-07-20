<?php

namespace App\Http\Controllers\Equipment;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Models\EquipmentType;
use App\Services\Equipment\EquipmentWorkbookService;
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

        return Inertia::render('Equipment/Workbook/Create');
    }

    /**
     *
     */
    public function store(Request $request)
    {
        //
        return 'store';
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
