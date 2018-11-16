<?php

use Illuminate\Database\Seeder;
use App\Customers;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Test Customers
        Customers::create([
            'cust_id'  => '2365',
            'name'     => 'Office of Chiropractors',
            'dba_name' => '',
            'address'  => '1113 Lindale Ave',
            'city'     => 'Fremont',
            'state'    => 'CA',
            'zip'      => '94536'
        ]);
        
        Customers::create([
            'cust_id'  => '2387',
            'name'     => 'Computer Training',
            'dba_name' => 'CTI',
            'address'  => '4375 Riverwood Dr',
            'city'     => 'Sacramento',
            'state'    => 'CA',
            'zip'      => '95814'
        ]);
        
        Customers::create([
            'cust_id'  => '8548',
            'name'     => 'Ship and Boat Building',
            'dba_name' => '',
            'address'  => '449 Francis Mine',
            'city'     => 'Chico',
            'state'    => 'CA',
            'zip'      => '95926'
        ]);
        
        Customers::create([
            'cust_id'  => '6325',
            'name'     => 'Abrasive Product Manufacturing',
            'dba_name' => 'APM',
            'address'  => '2064 Koontz Ln',
            'city'     => 'Northridge',
            'state'    => 'CA',
            'zip'      => '91324'
        ]);
        
        Customers::create([
            'cust_id'  => '7822',
            'name'     => 'All Other Foods Manufacturing',
            'dba_name' => '',
            'address'  => '4701 Park Ave',
            'city'     => 'Sacramento',
            'state'    => 'CA',
            'zip'      => '95814'
        ]);
        
        Customers::create([
            'cust_id'  => '6598',
            'name'     => 'Sew Apparel Manufacturing',
            'dba_name' => '',
            'address'  => '4148 Creekside Lane',
            'city'     => 'Ventura',
            'state'    => 'CA',
            'zip'      => '93001'
        ]);
        
        Customers::create([
            'cust_id'  => '4128',
            'name'     => 'Financial Investment Activities',
            'dba_name' => '',
            'address'  => '1900 Park St',
            'city'     => 'Oakland',
            'state'    => 'CA',
            'zip'      => '94612'
        ]);
        
        Customers::create([
            'cust_id'  => '7821',
            'name'     => 'Meat Markets',
            'dba_name' => 'MM Industries',
            'address'  => '3929 Paradise Lane',
            'city'     => 'Riverside',
            'state'    => 'CA',
            'zip'      => '92501'
        ]);
        
        Customers::create([
            'cust_id'  => '6970',
            'name'     => 'Theater Companies and Dinner Theaters',
            'dba_name' => '',
            'address'  => '1985 Ford St',
            'city'     => 'San Jose',
            'state'    => 'CA',
            'zip'      => '95131'
        ]);
        
        Customers::create([
            'cust_id'  => '1593',
            'name'     => 'Emergency Relief Services',
            'dba_name' => 'ERS International',
            'address'  => '4114 Hamill Ave',
            'city'     => 'San Diego',
            'state'    => 'CA',
            'zip'      => '92111'
        ]);
    }
}
