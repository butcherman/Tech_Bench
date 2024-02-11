<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactSite;
use App\Models\CustomerSite;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     *  Create 150 random customers
     */
    public function run()
    {
        /**
         * Create 150 new customers.  Assign them a random number of sub sites (up to five)
         */
        for ($i = 0; $i < 150; $i++) {
            $newCust = Customer::factory()->create();
            $newSite = CustomerSite::factory()->create([
                'cust_id' => $newCust->cust_id,
            ]);
            $newCust->primary_site_id = $newSite->cust_site_id;
            $newCust->save();

            // Pick a random number, if it has a factor of 7, assign sub sites
            $randomNum = rand(1, 50);
            if ($randomNum % 7 === 0) {
                CustomerSite::factory()->count(rand(1, 5))->create([
                    'cust_id' => $newCust->cust_id,
                ]);
            }
        }

        // $custList = Customer::all();

        /**
         * Assign 0-5 contacts to each customer
         */
        // foreach ($custList as $cust) {
        //     $newContactList = CustomerContact::factory()->count(rand(0, 5))->create(['cust_id' => $cust->cust_id]);
        //     if ($newContactList) {
        //         foreach ($newContactList as $cont) {
        //             CustomerContactSite::create([
        //                 'cont_id' => $cont->cont_id,
        //                 'cust_site_id' => $cust->primary_site_id,
        //             ]);
        //         }
        //     }
        // }
    }
}
