<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerSite;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     *  Create 150 random customers
     */
    public function run(): void
    {
        /**
         * Create 150 new customers.  Assign them a random number of sub sites (up to five)
         */
        for ($i = 0; $i < 150; $i++) {
            $newCust = Customer::factory()->create();

            // Pick a random number, if it has a factor of 7, assign sub sites
            $randomNum = rand(1, 50);
            if ($randomNum % 7 === 0) {
                CustomerSite::factory()->count(rand(1, 5))->create([
                    'cust_id' => $newCust->cust_id,
                ]);
            }
        }

        /**
         * Select 10 random customers to disable
         */
        $custList = Customer::inRandomOrder()->limit(10)->get();
        $custList->each(function ($customer) {
            $customer->deleted_reason = 'Testing Seeder';
            $customer->save();
            $customer->delete();
        });

        /**
         * Give the Administrator five bookmarks and five recent visits
         */
        $admin = User::find(1);

        $custList1 = Customer::inRandomOrder()->limit(5)->get();
        $custList1->each(function ($cust) use ($admin) {
            $cust->toggleBookmark($admin, true);
        });

        $custList2 = Customer::inRandomOrder()->limit(5)->get();
        $custList2->each(function ($cust) use ($admin) {
            $cust->touchRecent($admin);
        });
    }
}
