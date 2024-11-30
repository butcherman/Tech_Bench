<?php

namespace App\Http\Controllers\TechTip;

use App\Enums\CrudAction;
use App\Events\TechTip\NotifiableCommentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\TechTipCommentRequest;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Services\TechTip\TechTipCommentService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TechTipCommentController extends Controller
{
    public function __construct(protected TechTipCommentService $svc) {}

    /**
     * Display a listing of any Flagged Comments for a specific Tech TIp
     */
    public function index(): Response
    {
        $this->authorize('manage', TechTipComment::class);

        return Inertia::render('TechTips/Comments/Index', [
            'tip-data' => null, //  $techTip,
            'flagged-comments' => [], //  $techTip->TechTipComment()
            // ->has('Flags')
            // ->get()
            // ->makeVisible('Flags'),
        ]);
    }

    /**
     * Store a newly created Comment.
     */
    public function store(TechTipCommentRequest $request, TechTip $techTip): RedirectResponse
    {
        $newComment = $this->svc->createComment(
            $request->safe()->collect(),
            $techTip,
            $request->user()
        );

        event(new NotifiableCommentEvent($newComment, CrudAction::Create));

        return back()->with('success', __('tips.comment.created'));
    }

    /**
     * Update the Comment.
     */
    public function update(
        TechTipCommentRequest $request,
        TechTip $techTip,
        TechTipComment $comment
    ): RedirectResponse {
        $this->svc->updateComment($request->safe()->collect(), $comment);

        return back()->with('success', __('tips.comment.updated'));
    }

    /**
     * Remove the Comment.
     */
    public function destroy(TechTipComment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $this->svc->destroyComment($comment);

        return back()->with('warning', 'Comment Deleted');
    }
}
