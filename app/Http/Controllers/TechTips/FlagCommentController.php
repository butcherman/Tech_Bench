<?php

namespace App\Http\Controllers\TechTips;

use App\Events\FlaggedTipCommentEvent;
use App\Http\Controllers\Controller;
use App\Models\TechTipComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlagCommentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $comment = TechTipComment::findOrFail($request->id);

        $comment->flagged = true;
        $comment->save();

        event(new FlaggedTipCommentEvent($comment, $request->user()));

        Log::alert('Comment "'.$comment->comment.'" has been flagged as inappropriate by '.$request->user()->full_name.' on Tech Tip ID '.$comment->tip_id);
        return back()->with(['message' => 'Comment has been flagged as inappropriate and will be reviewed by a System Administrator', 'type' => 'danger']);
    }
}
