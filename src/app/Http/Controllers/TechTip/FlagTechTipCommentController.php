<?php

namespace App\Http\Controllers\TechTip;

use App\Events\TechTip\TechTipCommentFlaggedEvent;
use App\Http\Controllers\Controller;
use App\Models\TechTipComment;
use App\Services\TechTip\TechTipCommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FlagTechTipCommentController extends Controller
{
    /**
     * Flag a Tech Tip Comment for review by administrator.
     */
    public function __invoke(
        Request $request,
        TechTipCommentService $svc,
        TechTipComment $comment
    ): RedirectResponse {
        $svc->flagComment($comment, $request->user());

        event(new TechTipCommentFlaggedEvent($comment));

        return back()->with('warning', __('tips.comment.flagged'));
    }
}
