<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DeletedTipsController extends Controller
{
    /**
     * Handle the incoming request.
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
