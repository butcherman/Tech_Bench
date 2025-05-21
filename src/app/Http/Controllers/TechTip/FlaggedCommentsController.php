<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTipComment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FlaggedCommentsController extends Controller
{
    /**
     * Show any Flagged Tech Tip Comments.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('TechTip/Admin/Comments', [
            'flagged-comments' => TechTipComment::has('Flags')
                ->get()
                ->makeVisible('Flags'),
        ]);
    }
}
