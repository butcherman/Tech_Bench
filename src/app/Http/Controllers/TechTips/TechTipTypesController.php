<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TipTypesRequest;
use App\Models\TechTip;
use App\Models\TechTipType;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TechTipTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/Types', [
            'tip-types' => TechTipType::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipTypesRequest $request)
    {
        $newType = TechTipType::create($request->toArray());

        Log::channel('tips')
            ->info(
                'New Tech Tip Type created by ' . $request->user()->username,
                $newType->toArray()
            );

        return back()->with('success', __('tips.tip-type.created'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipTypesRequest $request, TechTipType $tipType)
    {
        $tipType->update($request->toArray());

        Log::channel('tips')
            ->info(
                'Tech Tip Type updated by ' . $request->user()->username,
                $tipType->toArray()
            );

        return back()->with('success', __('tips.tip-type.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, TechTipType $tipType)
    {
        $this->authorize('manage', TechTip::class);

        try {
            $tipType->delete();
            Log::channel('tips')
                ->notice(
                    'Tech Tip Type deleted by ' . $request->user()->username,
                    $tipType->toArray()
                );
        } catch (QueryException $e) {
            CheckDatabaseError::check(
                $e,
                __('tips.tip-type.in-use')
            );
        }

        return back()->with('warning', __('tips.tip-type.deleted'));
    }
}
