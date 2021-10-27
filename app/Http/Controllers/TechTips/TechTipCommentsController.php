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

class TechTipCommentsController extends Controller
{
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
        return TechTipComment::where('tip_id', $request->tip_id)->with('User')->get();
    }

    /**
     * Get the comments for a specific Tech Tip
     * TODO - This method can go away????
     */
    // public function show($id)
    // {
    //     return TechTip::where('tip_id', $id)->get();
    // }

    /**
     * Edit function will flag a Tech Tip as innapropriate
     */
    public function edit($id)
    {
        $comment = TechTipComment::find($id);
        $comment->update(['flagged' => true]);

        event(new TechTipCommentFlaggedEvent($comment));
        return TechTipComment::where('tip_id', $comment->tip_id)->with('User')->get();
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
        return TechTipComment::where('tip_id', $comment->tip_id)->with('User')->get();
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
        return TechTipComment::where('tip_id', $comment->tip_id)->with('User')->get();
    }
}
