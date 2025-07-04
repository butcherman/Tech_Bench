<?php

namespace App\Http\Controllers\Equipment;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Models\EquipmentType;
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
        return Inertia::render('Equipment/Workbook/Index', [
            'equipment-list' => CacheData::equipmentCategories(),
        ]);
    }

    /**
     * Show the Workbook Editor to create a new workbook
     */
    public function create(EquipmentType $equipiment_type): Response
    {
        return Inertia::render('Equipment/Workbook/Create', [
            'equipment-type' => $equipiment_type,
        ]);
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
    public function show(string $id)
    {
        //
        return 'show';
    }

    /**
     *
     */
    public function edit(string $id)
    {
        return Inertia::render('Equipment/Workbook/Edit');
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
