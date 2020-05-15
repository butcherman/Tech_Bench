<?php

namespace Tests\Unit\User;

use App\Domains\Users\UserList;
use App\User;
use Tests\TestCase;

class UserListTest extends TestCase
{
    protected $userObj, $testUsers;

    public function setUp():void
    {
        Parent::setup();

        $this->testUsers = factory(User::class, 9)->create();
        $this->userObj   = new UserList;
    }

    public function test_get_active_users()
    {
        $data = $this->userObj->getActiveUsers();
        $this->assertEquals($data->count(), 10);  //  count is default admin plus the new 9 users
    }
}
