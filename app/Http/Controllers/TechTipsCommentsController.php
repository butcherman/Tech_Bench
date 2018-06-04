<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TechTipComments;

class TechTipsCommentsController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    //  Add a new tech tip
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate = ['tipID' => 'required', 'comment' => 'required'];
        
        TechTipComments::create([
            'tip_id' => $request->tipID,
            'user_id' => Auth::user()->user_id,
            'comment' => $request->comment
        ]);
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
            'comment' => $comment,
            'commentID' => $id
        ]);
    }

    //  Submit an edited comment
    public function update(Request $request, $id)
    {
        $request->validate = ['comment' => 'required'];
        
        TechTipComments::find($id)->update([
            'comment' => $request->comment
        ]);
    }

    //  Delete a comment
    public function destroy($id)
    {
        TechTipComments::find($id)->delete();
    }
}
