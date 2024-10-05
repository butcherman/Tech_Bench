<?php

namespace App\Http\Controllers\TechTips;

use App\Actions\TechTipPermissions;
use App\Enum\CrudAction;
use App\Events\TechTips\TechTipEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TechTipRequest;
use App\Models\EquipmentCategory;
use App\Models\TechTip;
use App\Models\TechTipType;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TechTipsController extends Controller
{
    use FileTrait;

    public function __construct(protected TechTipPermissions $permissions) {}

    /**
     * Display Search Page for Tech Tips
     */
    public function index(Request $request)
    {
        return Inertia::render('TechTips/Index', [
            'filter-data' => [
                'tip_types' => TechTipType::all(),
                'equip_types' => EquipmentCategory::with('EquipmentType')->get(),
            ],
            'can-create' => $request->user()->can('create', TechTip::class),
        ]);
    }

    /**
     * Show the form for creating a new Tech Tip.
     */
    public function create(Request $request)
    {
        $this->authorize('create', TechTip::class);

        return Inertia::render('TechTips/Create', [
            'tip-types' => TechTipType::all(),
            'permissions' => $this->permissions->get($request->user()),
        ]);
    }

    /**
     * Store a newly created Tech Tip in storage.
     */
    public function store(TechTipRequest $request)
    {
        $newTip = $request->createTechTip();

        event(new TechTipEvent($newTip, CrudAction::Create, ! $request->suppress));

        return redirect()
            ->route('tech-tips.show', $newTip->slug)
            ->with('success', __('tips.created'));
    }

    /**
     * Display the specified Tech Tip.
     */
    public function show(Request $request, TechTip $tech_tip)
    {
        // Load relationships
        $tech_tip->loadShowData();

        return Inertia::render('TechTips/Show', [
            'tip-data' => fn () => $tech_tip,
            'tip-equipment' => fn () => $tech_tip->EquipmentType,
            'tip-files' => fn () => $tech_tip->FileUpload,
            'tip-comments' => fn () => $tech_tip->TechTipComment,
            'permissions' => fn () => $this->permissions->get($request->user()),
            'is-fav' => fn () => $tech_tip->isFav($request->user()),
        ]);
    }

    /**
     * Show the form for editing the specified Tech Tip.
     */
    public function edit(Request $request, TechTip $tech_tip)
    {
        $this->authorize('update', $tech_tip);

        return Inertia::render('TechTips/Edit', [
            'tip-data' => $tech_tip->load(['EquipmentType', 'FileUpload'])
                ->makeVisible('tip_type_id'),
            'tip-types' => TechTipType::all(),
            'permissions' => $this->permissions->get($request->user()),
        ]);
    }

    /**
     * Update the specified Tech Tip in storage.
     */
    public function update(TechTipRequest $request, TechTip $tech_tip)
    {
        $tipData = $request->updateTechTip();

        event(new TechTipEvent($tipData, CrudAction::Update, ! $request->suppress));

        return redirect()->route('tech-tips.show', $tipData->slug)
            ->with('success', __('tips.updated'));
    }

    /**
     * Soft Delete the specified Tech Tip.
     */
    public function destroy(TechTip $tech_tip)
    {
        $this->authorize('delete', $tech_tip);

        $tech_tip->delete();

        return redirect()
            ->route('tech-tips.index')
            ->with('warning', __('tips.deleted'));
    }

    /**
     * Restore a Soft Deleted Tech Tip
     */
    public function restore(TechTip $techTip)
    {
        $this->authorize('manage', TechTip::class);

        $techTip->restore();

        return redirect()
            ->route('tech-tips.show', $techTip->slug)
            ->with('success', __('tips.restored'));
    }

    /**
     * Remove a Tech Tip from the Database
     */
    public function forceDelete(TechTip $techTip)
    {
        $this->authorize('manage', TechTip::class);

        $techTip->forceDelete();

        return redirect()->route('admin.tech-tips.deleted-tips')->with('warning', __('tips.deleted'));
    }
}
