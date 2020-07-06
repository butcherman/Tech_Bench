<?php

namespace App\Domains\User;

use App\CustomerFavs;
use App\TechTipFavs;
use Illuminate\Support\Facades\Log;

class SetUserFavorites
{
    public function toggleCustomerFavorite($custID, $userID)
    {
        $favData = CustomerFavs::where('cust_id', $custID)->where('user_id', $userID)->first();

        if($favData)
        {
            Log::info('Customer Favorite Removed.  Data - ', ['user_id' => $userID, 'cust_id' => $custID]);
            $favData->delete();
            return false;
        }

        Log::info('Customer Favorite Added.  Data - ', ['user_id' => $userID, 'cust_id' => $custID]);
        CustomerFavs::create([
            'user_id' => $userID,
            'cust_id' => $custID,
        ]);
        return true;
    }

    public function toggleTechTipFavorite($tipID, $userID)
    {
        $favData = TechTipFavs::where('tip_id', $tipID)->where('user_id', $userID)->first();

        if($favData)
        {
            Log::info('Tech Tip Favorite Removed.  Data - ', ['user_id' => $userID, 'tip_id' => $tipID]);
            $favData->delete();
            return false;
        }

        Log::info('Tech Tip Favorite Added.  Data - ', ['user_id' => $userID, 'tip_id' => $tipID]);
        TechTipFavs::create([
            'user_id' => $userID,
            'tip_id' => $tipID,
        ]);
        return true;
    }
}
