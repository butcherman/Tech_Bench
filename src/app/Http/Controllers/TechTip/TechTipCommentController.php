<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\TechTipCommentRequest;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Services\TechTip\TechTipCommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TechTipCommentController extends Controller
{
    public function __construct(protected TechTipCommentService $svc) {}

    /**
     * Save a new Tech Tip Comment
     */
    public function store(TechTipCommentRequest $request, TechTip $tech_tip): RedirectResponse
    {
        $this->svc->createComment(
            $request->safe()->collect(),
            $tech_tip,
            $request->user()
        );

        return back()->with('success', __('tips.comment.created'));
    }

    /**
     * Update an existing Tech Tip Comment
     */
    public function update(
        TechTipCommentRequest $request,
        TechTip $tech_tip,
        TechTipComment $comment
    ): RedirectResponse {
        $this->svc->updateComment($request->safe()->collect(), $comment);

        return back()->with('success', 'Comment Updated');
    }

    /**
     * Delete a Tech Tip Comment
     */
    public function destroy(TechTip $tech_tip, TechTipComment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $this->svc->destroyComment($comment);

        return back()->with('warning', 'Comment Deleted');
    }
}
