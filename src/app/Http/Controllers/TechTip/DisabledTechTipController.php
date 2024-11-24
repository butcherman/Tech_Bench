<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Inertia\Inertia;
use Inertia\Response;

class DisabledTechTipController extends Controller
{
    /**
     * Show Tech Tips that have been disabled.
     */
    public function __invoke(): Response
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/Deleted/Index', [
            'deleted-tips' => TechTip::onlyTrashed()
                ->get()
                ->makeHidden('href')
                ->makeVisible('deleted_at'),
        ]);
    }
}
