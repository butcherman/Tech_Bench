<?php

namespace App\Domains\TechTips;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\TechTipComments;

class GetTechTipComments
{
    public function execute($tipID)
    {
        $comments = TechTipComments::where('tip_id', $tipID)->with('user')->get();

        Log::debug('Tech Tip Comments for Tip ID '.$tipID.' retrieved.  Data Gathered - ', array($comments));
        return $this->canIEdit($comments);
    }

    //  Determine if the authorized user can edit or delete the comments gathered
    protected function canIEdit($commentList)
    {
        foreach($commentList as $comment)
        {
            if(Auth::user()->user_id == $comment->user->user_id)
            {
                $comment->edit = true;
            }
        }

        return $commentList;
    }
}
