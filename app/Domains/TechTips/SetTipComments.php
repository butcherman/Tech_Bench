<?php

namespace App\Domains\TechTips;

use App\Notifications\NewTechTipCommentNotification;
use App\TechTipComments;
use App\TechTips;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SetTipComments
{
    public function createTipComment($request, $user)
    {
        TechTipComments::create([
            'tip_id' => $request->tip_id,
            'user_id' => $user->user_id,
            'comment' => $request->comment,
        ]);

        //  Notify the tip owner and others who have commented or updated the Tech Tip
        $owners = TechTips::select(['user_id', 'updated_id'])->where('tip_id', $request->tip_id)->first();
        $others = TechTipComments::select('user_id')->where('tip_id', $request->tip_id)->get();

        $userList = [
            User::find($owners->user_id),
            User::find($owners->updated_id),
        ];
        foreach($others as $o)
        {
            $userList[] = User::find($o->user_id);
        }

        Notification::send($userList, new NewTechTipCommentNotification($user->full_name, $request->tip_id));

        return true;
    }

    public function deleteComment($commentID)
    {
        TechTipComments::find($commentID)->delete();
        return true;
    }
}
