<?php

namespace App\Http\Controllers\TechTips;

use Inertia\Inertia;

use Illuminate\Database\QueryException;

use App\Models\TechTip;
use App\Models\TechTipType;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TipTypeRequest;
use App\Events\TechTips\Admin\TipTypeCreatedEvent;
use App\Events\TechTips\Admin\TipTypeDeletedEvent;
use App\Events\TechTips\Admin\TipTypeUpdatedEvent;
use App\Events\TechTips\Admin\TipTypeDeleteFailedEvent;

class TechTipTypesController extends Controller
{
    /**
     * Display a listing off all Tech Tip Types
     */
    public function index()
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/TipTypes/Index', [
            'types' => TechTipType::all(),
        ]);
    }

    /**
     * Store a new Tech Tip Type
     */
    public function store(TipTypeRequest $request)
    {
        $type = TechTipType::create($request->only('description'));

        event(new TipTypeCreatedEvent($type));
        return back()->with([
            'message' => 'New Tech Tip Type created',
            'type' => 'success',
        ]);
    }

    /**
     * Update a Tech Tip Type
     */
    public function update(TipTypeRequest $request, $id)
    {
        $type = TechTipType::findOrFail($id);
        $type->update($request->only('description'));

        event(new TipTypeUpdatedEvent($type));
        return back()->with([
            'message' => 'Tech Tip Type has been updated',
            'type'    => 'success',
        ]);
    }

    /**
     * Remove a Tech Tip Type
     */
    public function destroy($id)
    {
        $type = TechTipType::findOrFail($id);
        $this->authorize('manage', TechTip::class);

        try
        {
            $type->delete();
        }
        //  The deletion may fail if the tip type is currently in use
        catch(QueryException $e)
        {
            event(new TipTypeDeleteFailedEvent($e));
            return back()->with([
                'message' => 'Unable to delete.  This Tech Tip Type is in use by some Tech Tips.',
                'type'    => 'danger',
            ]);
        }

        event(new TipTypeDeletedEvent($type));
        return back()->with([
            'message' => 'Tech Tip Type Deleted Successfully',
            'type'    => 'success',
        ]);
    }
}
