<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CustomerFavs;
use App\TechTipFavs;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Dashboard is the Logged In User home landing page
    public function index()
    {
        $custFavs = CustomerFavs::where('user_id', Auth::user()->user_id)
            ->LeftJoin('customers', 'customer_favs.cust_id', '=', 'customers.cust_id')
            ->get();
        $tipFavs = TechTipFavs::where('tech_tip_favs.user_id', Auth::user()->user_id)
            ->LeftJoin('tech_tips', 'tech_tips.tip_id', '=', 'tech_tip_favs.tip_id')
            ->get();
        
        return view('dashboard', [
            'custFavs' => $custFavs,
            'tipFavs'  => $tipFavs
        ]);
    }
}
