<?php

namespace App\Http\Controllers\TechTips;

use App\User;
use App\TechTips;
use App\TechTipComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Notifications\NewTechTipComment;
use Illuminate\Support\Facades\Notification;

class TechTipCommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Add a new Tech Tip Comment
    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required',
            'tipID' => 'required',
        ]);

        TechTipComments::create([
            'tip_id' => $request->tipID,
            'user_id' => Auth::user()->user_id,
            'comment' => $request->comment
        ]);

        $ownerID = TechTips::find($request->tipID)->user_id;
        $owner = User::find($ownerID);

        Notification::send($owner, new NewTechTipComment(Auth::user()->full_name, $request->tipID));

        return response()->json(['success' => true]);
    }

    //  Retrieve the comments for a tech tip
    public function show($id)
    {
        return TechTipComments::where('tip_id', $id)->with('user')->get();
    }

    //  Delete a comment
    public function destroy($id)
    {
        TechTipComments::find($id)->delete();

        Log::warning('A Tech Tip Comment (id# '.$id.') was deleted by User ID - '.Auth::user()->user_id);
        return response()->json(['success' => true]);
    }
}
