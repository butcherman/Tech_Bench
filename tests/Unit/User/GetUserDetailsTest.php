<?php

namespace Tests\Unit\User;

use App\Domains\User\GetUserDetails;
use Tests\TestCase;

class GetUserDetailsTest extends TestCase
{
    protected $testObj, $testUser;

    //  Seed the database with some default data and setup for testing
    public function setUp():void
    {
        Parent::setup();

        $this->testUser = $this->getTech()->makeVisible('username');
        $this->testObj  = new GetUserDetails($this->testUser->user_id);
    }

    //  Verify the correct data is pulled from the database
    public function test_get_user_data()
    {
        $userData = $this->testObj->getUserData();
        $this->assertEquals($userData->toArray(), $this->testUser->toArray());
    }

    //  Verify the user settings table can be successfully pulled and that the default data is valid
    public function test_get_user_settings()
    {
        $userSettings = $this->testObj->getUserSettings();
        $this->assertEquals($userSettings->user_id, $this->testUser->user_id);
        $this->assertEquals($userSettings->em_tech_tip, true);
        $this->assertEquals($userSettings->em_file_link, true);
        $this->assertEquals($userSettings->em_notification, true);
        $this->assertEquals($userSettings->auto_del_link, true);
    }

    //  Verify check for duplicate username and password function works
    public function test_check_for_duplicate()
    {
        $fakeUser = factory(\App\User::class)->make();

        $dupUsernameFalse = $this->testObj->checkForDuplicate('username', $fakeUser->username);
        $dupUsernameTrue  = $this->testObj->checkForDuplicate('username', $this->testUser->username);
        $dupEmailFalse    = $this->testObj->checkForDuplicate('email', $fakeUser->email);
        $dupEmailTrue     = $this->testObj->checkForDuplicate('email', $this->testUser->email);

        $this->assertNull($dupUsernameFalse);
        $this->assertEquals($dupUsernameTrue->full_name, $this->testUser->full_name);
        $this->assertNull($dupEmailFalse);
        $this->assertEquals($dupEmailTrue->full_name, $this->testUser->full_name);
    }
}
