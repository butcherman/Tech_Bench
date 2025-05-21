<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DisabledTipViewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TechTip $tech_tip)
    {
        $this->authorize('manage', $tech_tip);

        return Inertia::render('TechTip/Admin/Deleted/Show', [
            'equipment' => fn() => $tech_tip->Equipment,
            'files' => fn() => $tech_tip->Files,
            'tech-tip' => fn() => $tech_tip->load(['CreatedBy', 'UpdatedBy']),
        ]);
    }
}
