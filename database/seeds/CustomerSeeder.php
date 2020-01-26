<?php

use App\Customers;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Create 50 test customers
        factory(Customers::class, 50)->create();

        //  Create 10 more customers, the first is a parent, the remaining 9 are children of this parent
        $cust_id = factory(Customers::class)->create()->cust_id;
        factory(Customers::class, 9)->create([
            'parent_id' => $cust_id,
        ]);
    }
}
