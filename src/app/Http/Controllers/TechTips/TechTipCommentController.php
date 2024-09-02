<?php

namespace App\Http\Controllers\TechTips;

use App\Events\TechTips\TipCommentedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TechTipCommentRequest;
use App\Models\TechTip;
use App\Models\TechTipComment;
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
        $this->authorize('manage', TechTipComment::class);

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
        $techTip->TechTipComment()->save(new TechTipComment([
            'user_id' => $request->user()->user_id,
            'comment' => $request->comment_data,
        ]));

        return back()->with('success', __('tips.comment.created'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TechTipCommentRequest $request, TechTip $techTip, TechTipComment $comment)
    {
        $comment->update(['comment' => $request->comment_data]);

        return back()->with('success', __('tips.comment.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TechTipComment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('warning', 'Comment Deleted');
    }

    public function restore(Request $request, TechTipComment $comment)
    {
        $this->authorize('manage', TechTipComment::class);

        $flags = $comment->Flags;
        foreach ($flags as $flag) {
            $flag->delete();
        }

        Log::notice(
            'Tech Tip Comment restored by ' . $request->user()->username,
            $comment->toArray()
        );

        return back()->with('success', 'Comment Restored');
    }
}
