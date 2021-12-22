<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeletedTechTipsController extends Controller
{
    /**
     * View a list of deleted Tech Tips
     */
    public function __invoke(Request $request)
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/Deleted', [
            'list' => TechTip::onlyTrashed()->get()->makeVisible('deleted_at'),
        ]);
    }
}
