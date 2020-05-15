<?php

namespace Tests\Unit\User;

use Tests\TestCase;

use App\Domains\Users\GetUserDetails;

class GetUserDetailsTest extends TestCase
{
    protected $userObj, $testUser;

    public function setUp():void
    {
        Parent::setup();

        $this->testUser = factory(\App\User::class)->create()->makeVisible('username');
        $this->userObj  = new GetUserDetails($this->testUser->user_id);
    }

    //  Verify the correct data is pulled from the database
    public function test_get_user_data()
    {
        $userData = $this->userObj->getUserData();
        $this->assertEquals($userData->toArray(), $this->testUser->toArray());
    }

    //  Verify the user settings table can be successfully pulled and that the default data is valid
    public function test_get_user_settings()
    {
        $userSettings = $this->userObj->getUserSettings();
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

        $dupUsernameFalse = $this->userObj->checkForDuplicate('username', $fakeUser->username);
        $dupUsernameTrue  = $this->userObj->checkForDuplicate('username', $this->testUser->username);
        $dupEmailFalse    = $this->userObj->checkForDuplicate('email', $fakeUser->email);
        $dupEmailTrue     = $this->userObj->checkForDuplicate('email', $this->testUser->email);

        $this->assertNull($dupUsernameFalse);
        $this->assertEquals($dupUsernameTrue->full_name, $this->testUser->full_name);
        $this->assertNull($dupEmailFalse);
        $this->assertEquals($dupEmailTrue->full_name, $this->testUser->full_name);
    }
}
