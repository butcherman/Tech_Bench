<?php

namespace Tests\Unit\User;

use App\Domains\User\GetUserList;
use App\User;
use Tests\TestCase;

class GetUserListTest extends TestCase
{
    protected $testObj, $testUsers;

    //  Seed the database with some default data and setup for testing
    public function setUp():void
    {
        Parent::setup();

        $this->testUsers = factory(User::class, 10)->create();
        $this->testUsers = factory(User::class, 10)->create([
            'deleted_at' => NOW(),
        ]);
        $this->testObj  = new GetUserList;
    }

    public function test_get_active_users()
    {
        $res = $this->testObj->getActiveUsers();

        $this->assertCount(11, $res);
    }

    public function test_get_inactive_users()
    {
        $res = $this->testObj->getInactiveUsers();

        $this->assertCount(10, $res);
    }
}