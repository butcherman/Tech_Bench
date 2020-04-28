<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public $user;

    public function setUp():void
    {
        Parent::setup();

        $this->user = $this->getTech();
    }

    //  Test that the "full name attribute" works
    public function test_full_name_attribute()
    {
        $dbUser = User::find($this->user->user_id);

        $this->assertEquals($this->user->first_name.' '.$this->user->last_name, $dbUser->full_name);
    }
}
