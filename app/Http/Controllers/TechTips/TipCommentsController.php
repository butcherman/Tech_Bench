<?php

namespace App\Http\Controllers\TechTips;

use App\Domains\TechTips\GetTipComments;
use App\Domains\TechTips\SetTipComments;
use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\NewCommentrequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TipCommentsController extends Controller
{
    //  Store a new Tech Tip Comment
    public function store(NewCommentrequest $request)
    {
        (new SetTipComments)->createTipComment($request, Auth::user());
        Log::info('User '.Auth::user()->full_name.' comment on Tech Tip ID '.$request->tip_id.'.  Details - ', ['comment' => $request->comment]);
        return response()->json(['success' => true]);
    }

    //  Show comments for a Tech Tip
    public function show($id)
    {
        return (new GetTipComments)->execute($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    //  Delete a Tip Comment
    public function destroy($id)
    {
        (new SetTipComments)->deleteComment($id);
        Log::info('User '.Auth::user()->full_name.' deleted Tech Tip Comment ID '.$id);
        return response()->json(['success' => true]);
    }
}
