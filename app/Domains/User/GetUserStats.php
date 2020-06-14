<?php

namespace App\Domains\User;

use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use App\FileLInks;
use App\TechTipFavs;
use App\CustomerFavs;

class GetUserStats
{
    protected $userID;

    public function __construct($userID)
    {
        $this->userID = $userID;
    }

    //  Get all Customer Favorites assigned to the user
    public function getUserCustomerFavs()
    {
        $favs = CustomerFavs::where('user_id', $this->userID)->get();
        Log::debug('Customer Favorites for User ID '.$this->userID.' gathered.  Data - ', $favs->toArray());

        return $favs->makeHidden('Customers');
    }

    //  Check if a customer is listed as a favorite for the user
    public function checkCustomerForFav($custID)
    {
        return CustomerFavs::where('user_id', $this->userID)->where('cust_id', $custID)->first();
    }

    //  Get all Tech Tip Favorites assigned to the user
    public function getUserTipFavs()
    {
        $favs = TechTipFavs::where('user_id', $this->userID)->get();
        Log::debug('Tech Tip Favorites for User ID '.$this->userID.' gathered.  Data - ', $favs->toArray());

        return $favs->makeHidden('TechTips');
    }

    //  Get a count of acctive file links the user has
    public function getUserActiveLinks()
    {
        $activeLinks = FileLinks::where('user_id', $this->userID)->where('expire', '>', Carbon::now())->count();
        Log::debug('Retrieved count of active File Links for User ID'.$this->userID.'. Data - ', ['Active Links' => $activeLinks]);

        return $activeLinks;
    }

    //  Get the total count of file links the user has
    public function getUserTotalLinks()
    {
        $totalLinks  = FileLinks::where('user_id', $this->userID)->count();
        Log::debug('Retrieved count of total File Links for User ID'.$this->userID.'. Data - ', ['Total Links' => $totalLinks]);

        return $totalLinks;
    }
}
