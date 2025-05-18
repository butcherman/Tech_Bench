<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Services\TechTip\TechTipCommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FlagTipController extends Controller
{
    public function __construct(protected TechTipCommentService $svc) {}

    /**
     * Flag a Tech Tip Comment for Administration Review.
     */
    public function __invoke(Request $request, TechTip $tech_tip, TechTipComment $comment): RedirectResponse
    {
        $this->svc->flagComment($comment, $request->user());

        return back()->with('warning', __('tips.comment.flagged'));
    }
}
