<?php

namespace App\Http\Controllers\TechTips;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Traits\TechTipTrait;

use App\Models\TechTip;
use App\Models\TechTipType;
use App\Models\EquipmentCategory;
use App\Models\UserTechTipBookmark;

use App\Events\TechTips\TechTipCreatedEvent;
use App\Events\TechTips\TechTipDeletedEvent;
use App\Events\TechTips\TechTipForceDeletedEvent;
use App\Events\TechTips\TechTipRestoredEvent;
use App\Events\TechTips\TechTipUpdatedEvent;
use App\Http\Requests\TechTips\CreateTipRequest;
use App\Http\Requests\TechTips\UpdateTipRequest;
use App\Models\TechTipFile;
use App\Traits\FileTrait;

class TechTipsController extends Controller
{
    use TechTipTrait;
    // use FileTrait;

    /**
     * Tech Tips search page
     */
    public function index(Request $request)
    {
        $filterData = [
            'tip_types'   => TechTipType::all(),
            'equip_types' => EquipmentCategory::with('EquipmentType')->get(),
        ];

        return Inertia::render('TechTips/Index', [
            'filter_data' => $filterData,
            'create'      => $request->user()->can('create', TechTip::class),
        ]);
    }

    /**
     * Show the form for creating a new Tech Tip
     */
    public function create()
    {
        $this->authorize('create', TechTip::class);

        return Inertia::render('TechTips/Create', [
            'tip_types' => TechTipType::all(),
            'equipment' => EquipmentCategory::with('EquipmentType')->get(),
        ]);
    }

    /**
     * Store a newly created Tech Tip
     */
    public function store(CreateTipRequest $request)
    {
        //  Create the new Tip
        $newTip = TechTip::create([
            'user_id'     => $request->user()->user_id,
            'tip_type_id' => $request->tip_type_id,
            'sticky'      => $request->sticky,
            'subject'     => $request->subject,
            'slug'        => Str::slug($request->subject),
            'details'     => $request->details,
        ]);

        $this->addEquipment($newTip->tip_id, $request->equipment);
        $this->processNewFiles($newTip->tip_id, true);

        event(new TechTipCreatedEvent($newTip, !$request->noEmail));
        return redirect(route('tech-tips.show', $newTip->slug));
    }

    /**
     * Display the Tech Tip
     */
    public function show($id)
    {
        //  Check if we are passing the Tech Tip name or number
        if(is_numeric($id))
        {
            //  To keep things uniform, redirect to a link that has the Tech Tip subject rather than the ID
            $tip = TechTip::findOrFail($id);
            return redirect(route('tech-tips.show', $tip->slug));
        }

        //  Pull the Tech Tip Information
        $tip = TechTip::where('slug', $id)
                ->with('EquipmentType')
                ->with('FileUploads')
                ->with('TechTipComment.User')
                ->firstOrFail()
                ->makeHidden(['summary', 'sticky']);

        //  Determine if the Tech Tip is bookmarked by the user
        $isFav = UserTechTipBookmark::where('user_id', Auth::user()->user_id)
                    ->where('tip_id', $tip->tip_id)
                    ->count();

        return Inertia::render('TechTips/Show', [
            'tip' => $tip,
            //  User Permissions for Tech Tips
            'user_data' => [
                'fav' => $isFav,
                'permissions' => [
                    'edit'      => Auth::user()->can('update', $tip),
                    'delete'    => Auth::user()->can('delete', $tip),
                    'manage'    => Auth::user()->can('manage', TechTip::class),
                    'comment'   => [
                        'create'  => Auth::user()->can('create', TechTipComment::class),
                        'manage'  => Auth::user()->can('manage', TechTipComment::class),
                    ],
                ],
            ]
        ]);
    }

    /**
     * Show the form to Edit the Tech Tip
     */
    public function edit($id)
    {
        $tip = TechTip::with(['EquipmentType', 'FileUploads'])->findOrFail($id)->makeVisible(['tip_type_id']);
        $this->authorize('update', $tip);

        return Inertia::render('TechTips/Edit', [
            'data'      => $tip,
            'tip_types' => TechTipType::all(),
            'equipment' => EquipmentCategory::with('EquipmentType')->get(),
        ]);
    }

    /**
     * Update the Tech Tip
     */
    public function update(UpdateTipRequest $request, $id)
    {
        //  Update the Tech Tip
        $tip = TechTip::findOrFail($id);
        $tip->update([
            'updated_id'  => $request->user()->user_id,
            'tip_type_id' => $request->tip_type_id,
            'sticky'      => $request->sticky,
            'subject'     => $request->subject,
            'slug'        => Str::slug($request->subject),
            'details'     => $request->details,
        ]);

        $this->processUpdatedEquipment($tip->tip_id, $request->equipment);
        $this->removeFiles($tip->tip_id, $request->removedFiles);
        $this->processNewFiles($tip->tip_id);

        event(new TechTipUpdatedEvent($tip, $request->resendNotif));
        return redirect(route('tech-tips.show', $tip->slug))->with([
            'message' => 'Tech Tip Updated',
            'type'    => 'success',
        ]);
    }

    /**
     * Soft Delete the Tech Tip
     */
    public function destroy($id)
    {
        $tip = TechTip::findOrFail($id);
        $this->authorize('delete', $tip);
        $tip->delete();

        event(new TechTipDeletedEvent($tip));
        return redirect(route('tech-tips.index'))->with([
            'message' => 'Tech Tip Deleted',
            'type'    => 'danger',
        ]);
    }

    /**
     * Restore a Tech Tip that was Soft Deleted
     */
    public function restore($id)
    {
        $tip = TechTip::withTrashed()->find($id);
        $this->authorize('manage', $tip);
        $tip->restore();

        event(new TechTipRestoredEvent($tip));
        return redirect(route('tech-tips.show', $tip->slug))->with([
            'message' => 'Tech Tip Restored',
            'type'    => 'success',
        ]);
    }

    /**
     * Permanently delete a Tech Tip from the database
     */
    public function forceDelete($id)
    {
        $tip = TechTip::withTrashed()->find($id);
        $this->authorize('manage', $tip);

        //  Determine if the Tech Tip has files that need to be deleted
        $files = TechTipFile::where('tip_id', $tip->tip_id)->get();
        foreach($files as $file)
        {
            $file->delete();
            $this->deleteFile($file->file_id);
        }

        $tip->forceDelete();

        event(new TechTipForceDeletedEvent($tip));
        return redirect(route('admin.tips.deleted'))->with([
            'message' => 'Tech Tip Permanently Deleted',
            'type'    => 'danger',
        ]);
    }
}
