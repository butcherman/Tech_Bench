<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //  Generic confirmation box to confirm file deletes or actions that cannot be undone
    public function confirmDialog()
    {
        return view('_inc.confirmDialog');
    }
    
    public function about()
    {
        echo 'about';
    }
}
