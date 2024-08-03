<?php

namespace App\Http\Controllers\TechTips;

use App\Actions\BuildTechTipPermissions;
use App\Enum\CrudAction;
use App\Events\File\FileDataDeletedEvent;
use App\Events\TechTips\TechTipEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TechTipRequest;
use App\Models\EquipmentCategory;
use App\Models\TechTip;
use App\Models\TechTipType;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TechTipsController extends Controller
{
    use FileTrait;

    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('create', TechTip::class);

        return Inertia::render('TechTips/Create', [
            'tip-types' => TechTipType::all(),
            'permissions' => BuildTechTipPermissions::build($request->user()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TechTipRequest $request)
    {
        $newTip = $request->createTechTip();

        Log::channel('tip')
            ->info('New Tech Tip created by ' . $request->user()->username, $newTip->toArray());
        event(new TechTipEvent($newTip, CrudAction::Create, !$request->suppress));

        return redirect()
            ->route('tech-tips.show', $newTip->slug)
            ->with('success', __('tips.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, TechTip $tech_tip)
    {
        // Increase the view counter
        $tech_tip->increment('views');
        // Load relationships
        $tech_tip->load(['CreatedBy', 'UpdatedBy'])
            ->CreatedBy->makeHidden(['email', 'initials', 'role_name', 'username']);

        if ($tech_tip->UpdatedBy) {
            $tech_tip->UpdatedBy
                ->makeHidden(['email', 'initials', 'role_name', 'username']);
        }

        return Inertia::render('TechTips/Show', [
            'tip-data' => $tech_tip,
            'tip-equipment' => $tech_tip->EquipmentType,
            'tip-files' => $tech_tip->FileUpload,
            'tip-comments' => $tech_tip->TechTipComment,
            'permissions' => BuildTechTipPermissions::build($request->user()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TechTip $tech_tip)
    {
        $this->authorize('update', $tech_tip);

        return Inertia::render('TechTips/Edit', [
            'tip-data' => $tech_tip->load(['EquipmentType', 'FileUpload'])
                ->makeVisible('tip_type_id'),
            'tip-types' => TechTipType::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TechTipRequest $request, TechTip $tech_tip)
    {
        $tipData = $request->updateTechTip();

        Log::channel('tip')
            ->info('Tech Tip updated by ' . $request->user()->username, $tipData->toArray());
        event(new TechTipEvent($tipData, CrudAction::Update, !$request->suppress));

        return redirect()->route('tech-tips.show', $tipData->slug)
            ->with('success', __('tips.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, TechTip $tech_tip)
    {
        $this->authorize('delete', $tech_tip);

        $tech_tip->delete();

        Log::channel('tip')
            ->notice('Tech Tip deleted by ' . $request->user()->username, $tech_tip->toArray());

        return redirect()
            ->route('tech-tips.index')
            ->with('warning', __('tips.deleted'));
    }

    public function restore(Request $request, TechTip $techTip)
    {
        $this->authorize('manage', TechTip::class);

        $techTip->restore();

        Log::channel('tip')->notice('Tech Tip restored by ' . $request->user()->username, $techTip->toArray());

        return redirect()
            ->route('tech-tips.show', $techTip->slug)
            ->with('success', __('tips.restored'));
    }

    public function forceDelete(Request $request, TechTip $techTip)
    {
        $this->authorize('manage', TechTip::class);

        // Get any Files attached to tip
        $fileList = $techTip->FileUpload->pluck('file_id')->toArray();
        $techTip->forceDelete();

        foreach ($fileList as $fileId) {
            event(new FileDataDeletedEvent($fileId));
        }

        Log::channel('tip')->notice('Tech Tip Force Deleted by ' . $request->user()->username, $techTip->toArray());

        return redirect()->route('admin.tech-tips.deleted-tips')->with('warning', __('tips.deleted'));
    }
}
