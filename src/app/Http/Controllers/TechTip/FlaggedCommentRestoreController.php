<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTipComment;
use App\Services\TechTip\TechTipCommentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FlaggedCommentRestoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TechTipCommentService $svc, TechTipComment $comment)
    {
        $this->authorize('manage', $comment);

        $svc->releaseComment($comment);

        return back()->with('success', 'Comment Restored');
    }
}
