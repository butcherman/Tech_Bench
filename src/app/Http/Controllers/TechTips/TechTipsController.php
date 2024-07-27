<?php

namespace App\Http\Controllers\TechTips;

use App\Actions\BuildTechTipPermissions;
use App\Enum\CrudAction;
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
    public function create()
    {
        $this->authorize('create', TechTip::class);

        return Inertia::render('TechTips/Create', [
            'tip-types' => TechTipType::all(),
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

        return redirect()->route('tech-tips.show', $newTip->slug);
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
            'permissions' => BuildTechTipPermissions::build($request->user()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
