<?php

namespace App\Http\Controllers\FileLinks;

use App\FileLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

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
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return response()->json(['note' => $linkNote[0]]);
    }
    
    //  Update the instruction details
    public function postIndex(Request $request, $id)
    {
        FileLinks::where('link_id', $id)->update([
            'note' => $request->note
        ]);
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data -', $request->toArray());
        return response()->json(['success' => true]);
    }
}
