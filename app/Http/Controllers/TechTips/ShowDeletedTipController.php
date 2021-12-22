<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowDeletedTipController extends Controller
{
    /**
     * Show a Tech Tip that has been soft deleted
     */
    public function __invoke($id)
    {
        $this->authorize('manage', TechTip::class);

        //  Pull the Tech Tip Information
        $tip = TechTip::where('slug', $id)
                ->withTrashed()
                ->with('EquipmentType')
                ->with('FileUploads')
                ->with('TechTipComment.User')
                ->firstOrFail()
                ->makeHidden(['summary', 'sticky']);

        return Inertia::render('TechTips/ShowDeleted', [
            'tip' => $tip,
        ]);
    }
}
