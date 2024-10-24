<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Service\TechTips\TechTipCommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FlagCommentController extends Controller
{
    public function __construct(protected TechTipCommentService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(TechTip $techTip, TechTipComment $comment): RedirectResponse
    {
        $this->svc->flagComment($comment);

        return back()->with('warning', __('tips.comment.flagged'));
    }
}
