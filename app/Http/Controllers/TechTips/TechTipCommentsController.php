<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;

use App\Domains\TechTips\GetTechTipComments;
use App\Domains\TechTips\SetTechTipComments;

use App\Http\Requests\TechTipNewCommentRequest;

class TechTipCommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Add a new Tech Tip Comment
    public function store(TechTipNewCommentRequest $request)
    {
        (new SetTechTipComments)->createTipComment($request);
        return response()->json(['success' => true]);
    }

    //  Retrieve the comments for a tech tip
    public function show($id)
    {
        return (new GetTechTipComments)->execute($id);
    }

    //  Delete a comment
    public function destroy($id)
    {
        (new SetTechTipComments)->deleteTipComment($id);
        return response()->json(['success' => true]);
    }
}
