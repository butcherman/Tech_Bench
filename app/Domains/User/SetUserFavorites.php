<?php

namespace App\Domains\User;

use App\CustomerFavs;

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

        Log::info('Customer Favorite Addes.  Data - ', ['user_id' => $userID, 'cust_id' => $custID]);
        CustomerFavs::create([
            'user_id' => $userID,
            'cust_id' => $custID,
        ]);
        return true;
    }
}
