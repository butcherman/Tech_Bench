<?php

namespace App\Http\Controllers\TechTip;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\TechTipTypeRequest;
use App\Models\TechTip;
use App\Models\TechTipType;
use App\Services\TechTip\TechTipAdministrationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TechTipTypeController extends Controller
{
    public function __construct(protected TechTipAdministrationService $svc) {}

    /**
     * Display a listing of the Tech Tip Types.
     */
    public function index(): Response
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/Types', [
            'tip-types' => CacheFacade::techTipTypes(),
        ]);
    }

    /**
     * Store a newly created Tech Tip Type.
     */
    public function store(TechTipTypeRequest $request): RedirectResponse
    {
        $this->svc->createTechTipType($request->safe()->collect());

        return back()->with('success', __('tips.tip-type.created'));
    }

    /**
     * Update the Tech Tip Type.
     */
    public function update(TechTipTypeRequest $request, TechTipType $tipType): RedirectResponse
    {
        $this->svc->updateTechTipType($request->safe()->collect(), $tipType);

        return back()->with('success', __('tips.tip-type.updated'));
    }

    /**
     * Remove the Tech Tip Type.
     */
    public function destroy(TechTipType $tipType): RedirectResponse
    {
        $this->authorize('manage', TechTip::class);

        $this->svc->destroyTechTipType($tipType);

        return back()->with('warning', __('tips.tip-type.deleted'));
    }
}
