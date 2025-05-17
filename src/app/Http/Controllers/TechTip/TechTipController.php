<?php

namespace App\Http\Controllers\TechTip;

use App\Facades\CacheData;
use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\TechTipRequest;
use App\Models\TechTip;
use App\Services\TechTip\TechTipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TechTipController extends Controller
{
    public function __construct(protected TechTipService $svc) {}

    /**
     * Show Search Form for Tech Tips.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('TechTip/Index', [
            'permissions' => UserPermissions::techTipPermissions($request->user()),
            'filter-data' => [
                'tip_types' => CacheData::techTipTypes(),
                'equip_types' => CacheData::equipmentCategories(),
            ],
        ]);
    }

    /**
     * Show the form to create a new Tech Tip.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', TechTip::class);

        return Inertia::render('TechTip/Create', [
            'permissions' => UserPermissions::techTipPermissions($request->user()),
            'tip-types' => CacheData::techTipTypes(),
            'equip-types' => CacheData::equipmentCategorySelectBox(),
        ]);
    }

    /**
     * Store a new Tech Tip and process any attached files
     */
    public function store(TechTipRequest $request): RedirectResponse
    {
        $newTip = $this->svc->createTechTip(
            $request->safe()->collect(),
            $request->user(),
            session()->pull('tip-file', [])
        );

        return redirect(route('tech-tips.show', $newTip->slug))
            ->with('success', __('tips.created'));
    }

    public function show(string $id)
    {
        //
        // return 'show';
        return Inertia::render('TechTip/Show');
    }

    public function edit(string $id)
    {
        //
        // return 'edit';
        return Inertia::render('TechTip/Edit');
    }

    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    public function destroy(string $id)
    {
        //
        return 'destroy';
    }

    public function restore(string $id)
    {
        //
        return 'restore';
    }

    public function forceDelete(string $id)
    {
        //
        return 'force delete';
    }
}
