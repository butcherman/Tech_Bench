<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\User;
use App\TechTips;
use App\TechTipComments;
use App\Notifications\TechTipComment;

class TechTipsCommentsController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Submit the Tech Tip comment
    public function store(Request $request)
    {
        $request->validate(['tipID' => 'required', 'comment' => 'required']);
        
        TechTipComments::create([
            'tip_id'  => $request->tipID,
            'user_id' => Auth::user()->user_id,
            'comment' => $request->comment
        ]);
        
        $tipData = TechTips::find($request->tipID);
        $creator = User::find($tipData->user_id);
        
        $noteData = [
            'user'   => Auth::user()->first_name.' '.Auth::user()->last_name,
            'title'  => $tipData->subject,
            'tip_id' => $tipData->tip_id
        ];
        
        Log::info('New Tech Tip Comment Added', ['tip_id' => $request->tipID, 'user_id' => Auth::user()->user_id]);
        
        Notification::send($creator, new TechTipComment($noteData));
    }

    //  Show comments for a tech tip
    public function show($id)
    {
        $comments = TechTipComments::where('tip_id', $id)
            ->join('users', 'tech_tip_comments.user_id', '=', 'users.user_id')
            ->get();
        
        return view('tip.comments', [
            'comments' => $comments
        ]);
    }

    //  Edit a comment
    public function edit($id)
    {
        $comment = TechTipComments::find($id);
        
        return view('tip.form.editComment', [
            'comment'   => $comment,
            'commentID' => $id
        ]);
    }

    //  Submit an edited comment
    public function update(Request $request, $id)
    {
        $request->validate(['comment' => 'required']);
        
        TechTipComments::find($id)->update([
            'comment' => $request->comment
        ]);
        
        Log::info('Tech Tip Comment Updated', ['comment_id' => $id, 'user_id' => Auth::user()->user_id]);
    }

    //  Delete a comment
    public function destroy($id)
    {
        TechTipComments::find($id)->delete();
        
        Log::info('Tech Tip Comment Deleted', ['tip_id' => $id, 'user_id' => Auth::user()->user_id]);
    }
}
