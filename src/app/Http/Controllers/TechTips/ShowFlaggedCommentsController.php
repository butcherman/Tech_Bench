<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTipComment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowFlaggedCommentsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $this->authorize('manage', TechTipComment::class);

        return Inertia::render('TechTips/Comments/Index', [
            'flagged-comments' => TechTipComment::has('Flags')
                ->with('TechTip')
                ->get()
                ->makeVisible('Flags'),
        ]);
    }
}
