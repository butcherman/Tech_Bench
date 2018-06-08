<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserLinksController extends Controller
{
    //
    
    public function index()
    {
        echo 'file link home';
    }
    
    public function details($id)
    {
        echo 'file link '.$id;
    }
}
