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

    /**
     * Show a Tech Tip
     */
    public function show(Request $request, TechTip $tech_tip): Response
    {
        $tech_tip->wasViewed();
        $tech_tip->touchRecent($request->user());

        return Inertia::render('TechTip/Show', [
            'allow-comments' => fn() => config('tech-tips.allow_comments'),
            'equipment' => fn() => $tech_tip->Equipment,
            'files' => fn() => $tech_tip->Files,
            'is-fav' => fn() => $tech_tip->isFav($request->user()),
            'permissions' => fn() => UserPermissions::techTipPermissions($request->user()),
            'tech-tip' => fn() => $tech_tip->load(['CreatedBy', 'UpdatedBy']),

            /**
             * Deferred Data
             */
            'comments' => Inertia::defer(fn() => $tech_tip->Comments)
        ]);
    }

    /**
     * Show form to Edit an existing Tech Tip
     */
    public function edit(TechTip $tech_tip)
    {
        //
        // return 'edit';
        return Inertia::render('TechTip/Edit');
    }

    /**
     * Save updates to a Tech Tip
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Soft Delete an existing Tech Tip
     */
    public function destroy(TechTip $tech_tip): RedirectResponse
    {
        $this->authorize('delete', $tech_tip);

        $this->svc->destroyTechTip($tech_tip);

        return redirect(route('tech-tips.index'))
            ->with('danger', 'Tech Tip Disabled');
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
