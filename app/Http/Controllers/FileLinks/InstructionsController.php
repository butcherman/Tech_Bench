<?php

namespace App\Http\Controllers\FileLinks;

use App\FileLinks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InstructionsController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Get the instruction details    
    public function getIndex($id)
    {
        $linkNote = FileLinks::where('link_id', $id)->pluck('note');
        
        return response()->json(['note' => $linkNote[0]]);
    }
    
    //  Update the instruction details
    public function postIndex(Request $request, $id)
    {
        FileLinks::where('link_id', $id)->update([
            'note' => $request->note
        ]);
        
        return response()->json(['success' => true]);
    }
}
