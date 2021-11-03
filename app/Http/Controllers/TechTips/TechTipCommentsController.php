<?php

namespace App\Http\Controllers\TechTips;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Events\TechTips\TechTipCommentCreatedEvent;
use App\Events\TechTips\TechTipCommentDeletedEvent;
use App\Events\TechTips\TechTipCommentFlaggedEvent;
use App\Events\TechTips\TechTipCommentUpdatedEvent;
use App\Http\Requests\TechTips\TechTipCommentRequest;
use App\Http\Requests\TechTips\UpdateCommentRequest;
use Inertia\Inertia;

class TechTipCommentsController extends Controller
{
    /**
     * Index method will list all comments that have been flagged
     */
    public function index()
    {
        return Inertia::render('TechTips/Comments/Index', [
            'flagged' => TechTipComment::where('flagged', true)->with('User')->get(),
        ]);
    }

    /**
     * Store a newly created Tech Tip Comment
     */
    public function store(TechTipCommentRequest $request)
    {
        $comment = TechTipComment::create([
            'tip_id'  => $request->tip_id,
            'user_id' => $request->user()->user_id,
            'comment' => $request->comment,
        ]);

        event(new TechTipCommentCreatedEvent($comment));
        return back()->with([
            'message' => 'Comment Created',
            'type'    => 'success',
        ]);
    }

    /**
     * Show Method will un-flag a comment
     */
    public function show($id)
    {
        $comment = TechTipComment::findOrFail($id);
        $this->authorize('manage', TechTip::class);
        $comment->update(['flagged' => false]);

        return back()->with([
            'message' => 'Comment has been unflagged',
            'type'    => 'warning',
        ]);
    }

    /**
     * Edit function will flag a Tech Tip as innapropriate
     */
    public function edit($id)
    {
        $comment = TechTipComment::find($id);
        $comment->update(['flagged' => true]);

        event(new TechTipCommentFlaggedEvent($comment));
        return back();
    }

    /**
     * Update a Tech Tip Comment
     */
    public function update(UpdateCommentRequest $request, $id)
    {
        $comment = TechTipComment::find($id);
        $comment->update($request->only(['comment']));
        $comment->save();

        event(new TechTipCommentUpdatedEvent($comment));
        return back();
    }

    /**
     * Delete the Tech Tip Comment
     */
    public function destroy($id)
    {
        $comment = TechTipComment::find($id);
        $this->authorize('delete', $comment);
        $comment->delete();

        event(new TechTipCommentDeletedEvent($comment));
        return back()->with([
            'message' => 'Comment Deleted',
            'type'    => 'success',
        ]);
    }
}
