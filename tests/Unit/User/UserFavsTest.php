<?php

namespace Tests\Unit\User;

use Tests\TestCase;

use App\Customers;
use App\Domains\Users\UserFavs;
use App\TechTips;

class UserFavsTest extends TestCase
{
    protected $userObj, $testUser, $testCust, $testTip;

    public function setUp():void
    {
        Parent::setup();

        $this->testUser = $this->getTech();
        $this->testCust = factory(Customers::class)->create();
        $this->testTip  = factory(TechTips::class)->create();
        $this->actingAs($this->testUser)->userObj  = new UserFavs;
    }

    //  Test adding and removing a tech tip to user
    public function test_update_tech_tip_fav()
    {
        //  Add tech tip to favs
        $this->userObj->updateTechTipFav($this->testTip->tip_id);
        $this->assertDatabaseHas('tech_tip_favs', ['user_id' => $this->testUser->user_id, 'tip_id' => $this->testTip->tip_id]);

        //  Remove the tech tip from favs
        $this->userObj->updateTechTipFav($this->testTip->tip_id);
        $this->assertDatabaseMissing('tech_tip_favs', ['user_id' => $this->testUser->user_id, 'tip_id' => $this->testTip->tip_id]);
    }

    //  Test adding and removing a customer to user
    public function test_update_customer_fav()
    {
        //  Add tech tip to favs
        $this->userObj->updateCustomerFav($this->testCust->cust_id);
        $this->assertDatabaseHas('customer_favs', ['user_id' => $this->testUser->user_id, 'cust_id' => $this->testCust->cust_id]);

        //  Remove the tech tip from favs
        $this->userObj->updateCustomerFav($this->testCust->cust_id);
        $this->assertDatabaseMissing('customer_favs', ['user_id' => $this->testUser->user_id, 'cust_id' => $this->testCust->cust_id]);
    }
}
