<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DisabledTipController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTip/Admin/Deleted/Index', [
            'deleted-tips' => TechTip::onlyTrashed()
                ->get()
                ->makeVisible('deleted_at'),
        ]);
    }
}
