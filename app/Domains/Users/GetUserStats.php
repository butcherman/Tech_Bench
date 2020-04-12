<?php

namespace App\Domains\Users;

// use App\CustomerFileTypes;
// use App\Http\Resources\CustomerFileTypesCollection;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\User;
use App\CustomerFavs;
use App\TechTipFavs;
use App\FileLinks;

class GetUserStats
{
    protected $userID;

    public function __construct($userID = null)
    {
        $userID ? $this->userID = $userID : $this->userID = Auth::user()->user_id;
    }

    public function getUserCustomerFavs()
    {
        $custFavs    = CustomerFavs::where('user_id', $this->userID)
            ->with(array('Customers' => function($query){
                $query->select('cust_id', 'name');
            }))
            ->get();

        return $custFavs;
    }

    public function getUserTechTipFavs()
    {
        $tipFavs     = TechTipFavs::where('user_id', $this->userID)
            ->with(array('TechTips' => function($query){
                $query->select('tip_id', 'subject');
            }))
            ->get();

        return $tipFavs;
    }

    public function getUserActiveFileLinks()
    {
        $activeLinks = FileLinks::where('user_id', Auth::user()->user_id)->where('expire', '>', Carbon::now())->count();
        return $activeLinks;
    }

    public function getUserTotalLinks()
    {
        $totalLinks  = FileLinks::where('user_id', Auth::user()->user_id)->count();
        return $totalLinks;
    }
}
