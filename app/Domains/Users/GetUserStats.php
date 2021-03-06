<?php

namespace App\Domains\Users;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\FileLinks;
use App\TechTipFavs;
use App\CustomerFavs;

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

        Log::debug('Retrieved Customer favorites for User ID'.$this->userID.'. Data - ', array($custFavs));
        return $custFavs;
    }

    public function checkForCustomerFav($custID)
    {
        $isFav = CustomerFavs::where('user_id', $this->userID)->where('cust_id', $custID)->first();

        return $isFav ? true : false;
    }

    public function getUserTechTipFavs()
    {
        $tipFavs     = TechTipFavs::where('user_id', $this->userID)
            ->with(array('TechTips' => function($query){
                $query->select('tip_id', 'subject');
            }))
            ->get();

        Log::debug('Retrieved Tech Tip favorites for User ID'.$this->userID.'. Data - ', array($tipFavs));
        return $tipFavs;
    }

    public function checkForTechTipFav($tipID)
    {
        $isFav = TechTipFavs::where('user_id', $this->userID)->where('tip_id', $tipID)->first();

        return $isFav ? true : false;
    }

    public function getUserActiveFileLinks()
    {
        $activeLinks = FileLinks::where('user_id', $this->userID)->where('expire', '>', Carbon::now())->count();

        Log::debug('Retrieved count of active File Links for User ID'.$this->userID.'. Data - './** @scrutinizer ignore-type */$activeLinks.' found');
        return $activeLinks;
    }

    public function getUserTotalLinks()
    {
        $totalLinks  = FileLinks::where('user_id', $this->userID)->count();

        Log::debug('Retrieved count of total File Links for User ID'.$this->userID.'. Data - './** @scrutinizer ignore-type */$totalLinks.' found');
        return $totalLinks;
    }
}
