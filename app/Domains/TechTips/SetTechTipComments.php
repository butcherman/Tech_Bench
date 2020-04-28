<?php

namespace App\Domains\TechTips;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use App\User;
use App\TechTips;
use App\TechTipComments;

use App\Http\Requests\TechTipNewCommentRequest;

use App\Notifications\NewTechTipComment;

class SetTechTipComments
{
    //  Add a comment to a Tech Tip
    public function createTipComment(TechTipNewCommentRequest $request)
    {
        TechTipComments::create([
            'tip_id'  => $request->tip_id,
            'user_id' => Auth::user()->user_id,
            'comment' => $request->comment,
        ]);

        //  Notify the Tech Tip Owner
        $ownerID = TechTips::find($request->tip_id)->user_id;
        $owner   = User::find($ownerID);

        Notification::send($owner, new NewTechTipComment(Auth::user()->full_name, $request->tip_id));

        Log::info('User '.Auth::user()->full_name.' commented on Tech Tip ID '.$request->tip_id.'.  Details - ', array($request));
        return true;
    }

    //  Remove a comment from a Tech Tip
    public function deleteTipComment($commentID)
    {
        TechTipComments::find($commentID)->delete();

        Log::notice('A Tech Tip Comment (id# '.$commentID.') was deleted by '.Auth::user()->full_name);
        return true;
    }
}
