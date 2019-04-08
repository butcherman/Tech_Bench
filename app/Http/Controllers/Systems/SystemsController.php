<?php

namespace App\Http\Controllers\Systems;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Allow the user to select a category to view
    public function index()
    {
        echo 'select category';
    }
    
    //  Select the system type for the given category
    public function selectSys($cat)
    {
        echo 'select system';
    }
    
    //  Show the details of the selected system
    public function details($cat, $sys)
    {
        echo 'details';
    }
}
