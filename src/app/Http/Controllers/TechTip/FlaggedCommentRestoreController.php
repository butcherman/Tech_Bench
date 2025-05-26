<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTipComment;
use App\Services\TechTip\TechTipCommentService;
use Illuminate\Http\RedirectResponse;

class FlaggedCommentRestoreController extends Controller
{
    /**
     * Remove any set flags for a Tech Tip Comment
     */
    public function __invoke(TechTipCommentService $svc, TechTipComment $comment): RedirectResponse
    {
        $this->authorize('manage', $comment);

        $svc->releaseComment($comment);

        return back()->with('success', 'Comment Restored');
    }
}
