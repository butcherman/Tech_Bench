<?php

namespace App\Domains\Users;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\TechTipFavs;
use App\CustomerFavs;

class UserFavs
{
    protected $userID;

    public function __construct($userID = null)
    {
        $this->userID = isset($userID) ? $userID : Auth::user()->user_id;
    }

    public function updateTechTipFav($tipID)
    {
        //
    }

    public function updateCustomerFav($custID)
    {
        $favData = CustomerFavs::where('cust_id', $custID)->where('user_id', $this->userID)->first();

        if($favData)
        {
            Log::info('Customer Bookmark removed.  Detais', [
                'user_id' => $this->userID,
                'cust_id' => $custID,
            ]);
            $favData->delete();
        }
        else
        {
            CustomerFavs::create([
                'user_id' => $this->userID,
                'cust_id' => $custID,
            ]);
            Log::info('Customer Bookmark added.  Detais', [
                'user_id' => $this->userID,
                'cust_id' => $custID,
            ]);
        }

        return true;
    }
}
