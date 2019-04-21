<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('customer.index');
    }
    
    public function list()
    {
        $custList = Customers::all();
        
        return response()->json($custList);
    }
}
