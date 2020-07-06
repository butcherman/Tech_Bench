<?php

namespace Tests\Unit\User;

use App\Customers;
use App\Domains\User\SetUserFavorites;
use App\TechTips;
use Tests\TestCase;

class SetUserFavoritesTest extends TestCase
{
    protected $testUser, $testObj;

    public function setUp():void
    {
        Parent::setup();

        $this->testUser = $this->getTech();
        $this->testObj  = new SetUserFavorites;
    }

    public function test_toggle_customer_favorite()
    {
        $cust = factory(Customers::class)->create();

        $res = $this->testObj->toggleCustomerFavorite($cust->cust_id, $this->testUser->user_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_favs', ['cust_id' => $cust->cust_id, 'user_id' => $this->testUser->user_id]);

        //  Run it again to remove the user as a favorite
        $res = $this->testObj->toggleCustomerFavorite($cust->cust_id, $this->testUser->user_id);
        $this->assertFalse($res);
        $this->assertDatabaseMissing('customer_favs', ['cust_id' => $cust->cust_id, 'user_id' => $this->testUser->user_id]);
    }

    public function test_toggle_tech_tip_favorite()
    {
        $tip = factory(TechTips::class)->create();

        $res = $this->testObj->toggleTechTipFavorite($tip->tip_id, $this->testUser->user_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('tech_tip_favs', ['tip_id' => $tip->tip_id, 'user_id' => $this->testUser->user_id]);

        //  Run it again to remove the user as a favorite
        $res = $this->testObj->toggleTechTipFavorite($tip->tip_id, $this->testUser->user_id);
        $this->assertFalse($res);
        $this->assertDatabaseMissing('tech_tip_favs', ['tip_id' => $tip->tip_id, 'user_id' => $this->testUser->user_id]);
    }
}
