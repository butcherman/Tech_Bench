<?php

namespace App\Http\Controllers\TechTips;

use App\Events\NewTipCommentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\CommentRequest;
use App\Models\TechTipComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     *  Store a new comment on a Tech Tip
     */
    public function store(CommentRequest $request)
    {
        TechTipComment::create([
            'tip_id'  => $request->tip_id,
            'user_id' => $request->user()->user_id,
            'comment' => $request->comment,
        ]);

        // event(new EventsTechTipComment($request->tip_id, $request->comment));
        event(new NewTipCommentEvent($request->tip_id, $request->comment, $request->user()->full_name));

        Log::info('New comment has been created on Tech Tip ID '.$request->tip_id.' by '.$request->user()->username);
        return back()->with(['message' => 'Comment submitted', 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = TechTipComment::findOrFail($id);

        $this->authorize('delete', $comment);
        $comment->delete();

        Log::info('A Comment on Tech Tip ID '.$comment->tip_id.' has been deleted by '.Auth::user()->username.'.  Comment ID - '.$comment->id);

        return back()->with(['message' => 'Comment has been deleted', 'type' => 'danger']);
    }
}
