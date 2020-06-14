<?php

namespace Tests\Unit\User;

use Tests\TestCase;

use App\User;
use App\TechTips;
use App\Customers;
use App\FileLinks;
use App\TechTipFavs;
use App\CustomerFavs;

use App\Domains\User\GetUserStats;

class GetUserStatsTest extends TestCase
{
    protected $statObj, $testUser, $testCustomers, $testTips;

    //  Seed the database with some default data and setup for testing
    public function setUp():void
    {
        Parent::setup();

        $this->seed();
        $this->testUser      = User::all()->random(1)->first();
        $this->testCustomers = Customers::all()->random(10);
        $this->testTips      = TechTips::all()->random(10);

        //  Input the first five as favorites
        for($i = 0; $i < 5; $i++)
        {
            CustomerFavs::create([
                'user_id' => $this->testUser->user_id,
                'cust_id' => $this->testCustomers[$i]->cust_id,
            ]);

            TechTipFavs::create([
                'user_id' => $this->testUser->user_id,
                'tip_id'  => $this->testTips[$i]->tip_id,
            ]);
        }
        $this->statObj = new GetUserStats($this->testUser->user_id);

        //  Create five active file links, and five expired file links
        factory(FileLinks::class, 5)->create([
            'user_id' => $this->testUser->user_id,
        ]);
        factory(FileLinks::class, 5)->create([
            'user_id' => $this->testUser->user_id,
            'expire'  => date('Y-m-d', strtotime('-30 days')),
        ]);
    }

    //  Test that all of the users customer favorites are there - test will only count the results
    public function test_get_user_customer_favs()
    {
        $testFavs = $this->statObj->getUserCustomerFavs();
        $this->assertTrue($testFavs->count() == 5);
    }

    public function test_check_customer_for_fav_true()
    {
        $cust = factory(Customers::class)->create();
        CustomerFavs::create(['user_id' => $this->testUser->user_id, 'cust_id' => $cust->cust_id]);

        $res = $this->statObj->checkCustomerForFav($cust->cust_id);
        $this->assertEquals($res->cust_id, $cust->cust_id);
    }

    public function test_check_customer_for_fav_false()
    {
        $cust = factory(Customers::class)->create();

        $res = $this->statObj->checkCustomerForFav($cust->cust_id);
        $this->assertNull($res);
    }

    //  Test that all of the users tip favorites are there - test will only count the results
    public function test_get_user_tip_favs()
    {
        $testFavs = $this->statObj->getUserTipFavs();
        $this->assertTrue($testFavs->count() == 5);
    }

    //  Test the count of active file links for the user
    public function test_get_user_active_links()
    {
        $linkCount = $this->statObj->getUserActiveLinks();
        $this->assertEquals($linkCount, 5);
    }

    //  Test the count of total file links for the user
    public function test_get_user_total_links()
    {
        $linkCount = $this->statObj->getUserTotalLinks();
        $this->assertEquals($linkCount, 10);
    }
}
