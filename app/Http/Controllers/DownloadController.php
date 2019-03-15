<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    //
    
    public function index()
    {
        return response('download page');
    }
    
    public function downloadAll(Request $request)
    {
        return response('download all page');
    }
}
