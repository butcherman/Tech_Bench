<?php

namespace App\Http\Controllers\Equipment;

use App\Exceptions\Misc\FeatureDisabledException;
use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentWorkbookController extends Controller
{
    public function __construct()
    {
        if (!config('customer.enable_workbooks')) {
            throw new FeatureDisabledException;
        }
    }

    /**
     * Show a list of all equipment and link to workbook if it has one.
     */
    public function index(): Response
    {
        $this->authorize('update', EquipmentType::class);

        return Inertia::render('Equipment/Workbook/Index', [
            'equipment-list' => fn() => CacheData::equipmentCategories(),
        ]);
    }

    /**
     *
     */
    public function create()
    {
        //
        return 'create';
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
