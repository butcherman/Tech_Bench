<?php

namespace App\Http\Controllers\TechTips;

use App\Events\TechTips\TipCommentedEvent;
use App\Events\TechTips\TipCommentFlaggedEvent;
use App\Exceptions\TechTips\CommentFlaggedAlreadyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TechTipCommentRequest;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TechTipCommentController extends Controller
{
    /**
     * Display a listing of any Flagged Comments for a specific Tech Tip
     */
    public function index(TechTip $techTip)
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/Comments/Index', [
            'tip-data' => $techTip,
            'flagged-comments' => $techTip->TechTipComment()
                ->has('Flags')
                ->get()
                ->makeVisible('Flags'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TechTipCommentRequest $request, TechTip $techTip)
    {
        $newComment = $techTip->TechTipComment()->save(new TechTipComment([
            'user_id' => $request->user()->user_id,
            'comment' => $request->comment,
        ]));

        Log::channel('tip')
            ->info(
                'New Tech Tip Comment for Tip ID ' . $techTip->tip_id,
                $newComment->toArray()
            );

        event(new TipCommentedEvent($newComment));

        return back()->with('success', __('tips.comment.created'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TechTipCommentRequest $request, TechTip $techTip, TechTipComment $comment)
    {
        $comment->update($request->only(['comment']));

        Log::channel('tips')
            ->info(
                'Tech Tip Comment updated by ' . $request->user()->username,
                $comment->toArray()
            );

        return back()->with('success', __('tips.comment.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, TechTipComment $comment)
    {
        $this->authorize('manage', TechTip::class);

        $comment->delete();

        Log::channel('tips')
            ->notice(
                'Tech Tip Comment deleted by ' . $request->user()->username,
                $comment->toArray()
            );

        return back()->with('warning', 'Comment Deleted');
    }

    public function restore(Request $request, TechTipComment $comment)
    {
        $this->authorize('manage', TechTip::class);

        $flags = $comment->Flags;
        foreach ($flags as $flag) {
            $flag->delete();
        }

        Log::channel('tips')
            ->notice(
                'Tech Tip Comment restored by ' . $request->user()->username,
                $comment->toArray()
            );

        return back()->with('success', 'Comment Restored');
    }
}
