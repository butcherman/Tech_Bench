<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserCustomerBookmark;

trait CustomerEventsTrait
{
    protected function getUserList(int $cust_id, int $ignore_id)
    {
        $bookmarks = UserCustomerBookmark::where('cust_id', $cust_id)->where('user_id', '!=', $ignore_id)->get()->pluck('user_id')->toArray();
        $userList = User::find($bookmarks);

        return $userList;
    }
}