<?php

namespace App\Http\Controllers\TechTip;

use App\Facades\CacheFacade;
use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\TechTipRequest;
use App\Http\Resources\TechTipResource;
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
     * Display the Tech Tip Search page.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('TechTips/Index', [
            'filter-data' => [
                'tip_types' => CacheFacade::techTipTypes(),
                'equip_types' => CacheFacade::equipmentCategories(),
            ],
            'can-create' => $request->user()->can('create', TechTip::class),
        ]);
    }

    /**
     * Show the form for creating a new Tech Tip.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', TechTip::class);

        return Inertia::render('TechTips/Create', [
            'tip-types' => CacheFacade::techTipTypes(),
            'permissions' => UserPermissions::techTipPermissions(
                $request->user()
            ),
        ]);
    }

    /**
     * Store a newly created Tech Tip.
     */
    public function store(TechTipRequest $request): RedirectResponse
    {
        $fileList = $request->session()->has('tip-file')
            ? $request->session()->pull('tip-file')
            : [];

        $newTip = $this->svc->createTechTip(
            $request->safe()->collect(),
            $request->user(),
            $fileList
        );

        return redirect()
            ->route('tech-tips.show', $newTip->slug)
            ->with('success', __('tips.created'));
    }

    /**
     * Display the Tech Tip.
     */
    public function show(Request $request, TechTip $tech_tip): Response
    {
        return Inertia::render('TechTips/Show', [
            'tip-data' => fn () => new TechTipResource($tech_tip),
            'tip-equipment' => fn () => $tech_tip->EquipmentType,
            'tip-files' => fn () => $tech_tip->FileUpload,
            'tip-comments' => fn () => $tech_tip->TechTipComment,
            'permissions' => fn () => UserPermissions::techTipPermissions(
                $request->user()
            ),
            'is-fav' => fn () => $tech_tip->isFav($request->user()),
        ]);
    }

    /**
     * Show the form for editing the Tech Tip.
     */
    public function edit(Request $request, TechTip $tech_tip): Response
    {
        $this->authorize('update', $tech_tip);

        return Inertia::render('TechTips/Edit', [
            'tip-data' => $tech_tip->load(['EquipmentType', 'FileUpload'])
                ->makeVisible('tip_type_id'),
            'tip-types' => CacheFacade::techTipTypes(),
            'permissions' => UserPermissions::techTipPermissions(
                $request->user()
            ),
        ]);
    }

    /**
     * Update the Tech Tip.
     */
    public function update(TechTipRequest $request, TechTip $tech_tip): RedirectResponse
    {
        $fileList = $request->session()->has('tip-file')
            ? $request->session()->pull('tip-file')
            : [];

        $tipData = $this->svc->updateTechTip(
            $request->safe()->collect(),
            $tech_tip,
            $request->user(),
            $fileList
        );

        return redirect()->route('tech-tips.show', $tipData->slug)
            ->with('success', __('tips.updated'));
    }

    /**
     * Remove the Tech Tip.
     */
    public function destroy(TechTip $tech_tip): RedirectResponse
    {
        $this->authorize('delete', $tech_tip);

        $this->svc->destroyTechTip($tech_tip);

        return redirect()
            ->route('tech-tips.index')
            ->with('warning', __('tips.deleted'));
    }

    /**
     * Restore the Tech Tip.
     */
    public function restore(TechTip $tech_tip): RedirectResponse
    {
        $this->authorize('manage', TechTip::class);

        $this->svc->restoreTechTip($tech_tip);

        return redirect()
            ->route('tech-tips.show', $tech_tip->slug)
            ->with('success', __('tips.restored'));
    }

    /**
     * Force Delete the Tech Tip.
     */
    public function forceDelete(TechTip $tech_tip): RedirectResponse
    {
        $this->authorize('manage', TechTip::class);

        $this->svc->destroyTechTip($tech_tip, true);

        return redirect()
            ->route('admin.tech-tips.deleted-tips')
            ->with('warning', __('tips.deleted'));
    }
}
