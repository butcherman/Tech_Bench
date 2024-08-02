<?php

namespace App\Http\Controllers\TechTips;

use App\Events\TechTips\TipCommentFlaggedEvent;
use App\Exceptions\TechTips\CommentFlaggedAlreadyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TechTipCommentRequest;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\TechTipCommentFlag;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TechTipCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TechTip $techTip)
    {
        return Inertia::render('TechTips/Comments/Index', [
            'tip-data' => $techTip,
            'flagged-comments' => $techTip->TechTipComment()->has('Flags')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('TechTips/Comments/Index', [
            'flagged-comments' => TechTipComment::has('Flags')
                ->with('TechTip')
                ->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TechTipCommentRequest $request, TechTip $techTip)
    {
        $techTip->TechTipComment()->save(new TechTipComment([
            'user_id' => $request->user()->user_id,
            'comment' => $request->comment,
        ]));

        Log::channel('tips')->info('New Tech Tip Comment for Tip ID ' . $techTip->tip_id, [
            'comment' => $request->comment,
        ]);
        // TODO - Dispatch event to email

        return back()->with('success', __('tips.comment.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, TechTip $techTip, TechTipComment $comment)
    {
        try {
            $comment->flagComment();
            Log::stack(['daily', 'tips'])
                ->notice(
                    'Tech Tip comment has been flagged by ' . $request->user()->username,
                    $comment->toArray()
                );
            event(new TipCommentFlaggedEvent($comment));
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[1], [1062])) {
                throw new CommentFlaggedAlreadyException($request);
            } else {
                CheckDatabaseError::check($e);
            }
        }

        return back()->with('warning', __('tips.comment.flagged'));
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

        return back()->with('success', 'Comment Deleted');
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
