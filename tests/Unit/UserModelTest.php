<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;
use App\UserLogins;
// use App\UserPermissions;

class UserModelTest extends TestCase
{
    public $user;

    public function setUp():void
    {
        Parent::setup();

        $this->user = $this->getTech();
    }

    // public function test_user_has_userlogin()
    // {

    // }

    //  Test that the "full name attribute" works
    public function test_full_name_attribute()
    {
        // $user = $this->getTech();
        $dbUser = User::find($this->user->user_id);

        $this->assertEquals($this->user->first_name.' '.$this->user->last_name, $dbUser->full_name);
    }
}
