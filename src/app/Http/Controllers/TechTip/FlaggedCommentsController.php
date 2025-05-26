<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTipComment;
use Inertia\Inertia;
use Inertia\Response;

class FlaggedCommentsController extends Controller
{
    /**
     * Show any Flagged Tech Tip Comments.
     */
    public function __invoke(): Response
    {
        $this->authorize('manage', TechTipComment::class);

        return Inertia::render('TechTip/Admin/Comments', [
            'flagged-comments' => TechTipComment::has('Flags')
                ->get()
                ->makeVisible('Flags'),
        ]);
    }
}
