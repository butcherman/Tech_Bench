<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Models\TechTipComment;
use App\Services\TechTip\TechTipCommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClearTechTipCommentFlagController extends Controller
{
    public function __construct(protected TechTipCommentService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(TechTipComment $comment): RedirectResponse
    {
        $this->authorize('manage', TechTipComment::class);

        $this->svc->removeCommentFlag($comment);

        return back()->with('success', 'Comment Restored');
    }
}
