<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\UserCustomerBookmark;
use App\Models\UserCustomerRecent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerRemoveBookmarksJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

    protected $cust;

    /**
     * Create a new job instance
     */
    public function __construct(Customer $cust)
    {
        $this->cust = $cust;
    }

    /**
     * Remove a customer from all users bookmarks and recent lists
     */
    public function handle()
    {
        //  Remove the customer from users 'recent' list
        UserCustomerRecent::where('cust_id', $this->cust->cust_id)->delete();
        //  Remove the customer from users 'bookmarks' list
        UserCustomerBookmark::where('cust_id', $this->cust->cust_id)->delete();

        Log::debug('Removed Customer '.$this->cust->name.' from all users bookmarks and recent customers list');
    }
}
