<?php

namespace App\Http\Controllers\TechTip;

use App\Facades\CacheData;
use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TechTipController extends Controller
{
    /**
     * Show Search Form for Tech Tips.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('TechTips/Index', [
            'permissions' => UserPermissions::techTipPermissions($request->user()),
            'filter-data' => [
                'tip_types' => CacheData::techTipTypes(),
                'equip_types' => CacheData::equipmentCategories(),
            ],
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
