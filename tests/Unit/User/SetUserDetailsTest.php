<?php

namespace Tests\Unit\User;

use Carbon\Carbon;
use Tests\TestCase;

use Illuminate\Support\Facades\Notification;

use App\Domains\Users\SetUserDetails;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserBasicAccountRequest;
use App\Http\Requests\UserNotificationSettingsRequest;

class SetUserDetailsTest extends TestCase
{
    protected $userObj, $testUser;

    public function setUp():void
    {
        Parent::setup();

        $this->testUser = factory(\App\User::class)->make()->makeVisible(['user_id', 'role_id', 'username'])->makeHidden('full_name');
        $this->userObj  = new SetUserDetails;
    }

    //  Test that a new user can be successfully created
    public function test_create_new_user()
    {
        Notification::fake();

        $user = new UserCreateRequest($this->testUser->toArray());

        $this->actingAs($this->getInstaller());
        $newUser = $this->actingAs($this->getInstaller())->userObj->createNewUser($user);
        $this->testUser->user_id = $newUser;

        //  Verify the user and associated references have been created
        $this->assertDatabaseHas('users', $this->testUser->toArray());
        $this->assertDatabaseHas('user_settings', ['user_id' => $newUser]);
        $this->assertDatabaseHas('user_initializes', ['username' => $this->testUser->username]);
    }

    //  Test disabling a user
    public function test_disable_user()
    {
        $user = $this->getTech()->makeHidden('full_name');
        $this->actingAs($this->getInstaller())->userObj->disableUser($user->user_id);
        $this->assertSoftDeleted('users', $user->toArray());
    }

    //  Test updating the user as an administrator
    public function test_update_user_details()
    {
        $updatedUser = $this->testUser;
        $updatedUser->first_name = 'John';
        $updatedUser->last_name  = 'Doe';
        $user = new UserBasicAccountRequest($updatedUser->toArray());

        $this->actingAs($this->getInstaller())->userObj->updateUserDetails($user, $updatedUser->user_id);
        $this->assertDatabaseHas('users', $updatedUser->toArray());
    }

    //  Test updating the user as a regular user
    public function test_update_user_details_own_details()
    {
        $updatedUser = collect($this->testUser);
        $updatedUser->forget('username');
        $updatedUser->first_name = 'Bruce';
        $updatedUser->last_name  = 'Wayne';
        $user = new UserBasicAccountRequest($updatedUser->toArray());

        $this->actingAs($this->getTech())->userObj->updateUserDetails($user);
        $this->assertDatabaseHas('users', $updatedUser->toArray());
    }

    //  Test updating the users email notifications as administrator
    public function test_update_user_notifications()
    {
        $user = $this->getTech();
        $updateData = new UserNotificationSettingsRequest([
            'em_tech_tip'     => false,
            'em_file_link'    => false,
            'em_notification' => false,
            'auto_del_link'   => false,
        ]);

        $this->actingAs($this->getInstaller())->userObj->updateUserNotifications($updateData, $user->user_id);
        $updateData->user_id = $user->user_id;
        $this->assertDatabaseHas('user_settings', $updateData->toArray());
    }

    //  Test updating the users email notifications
    public function test_update_user_notifications_own_settings()
    {
        $user = $this->getTech();
        $updateData = new UserNotificationSettingsRequest([
            'em_tech_tip'     => false,
            'em_file_link'    => false,
            'em_notification' => false,
            'auto_del_link'   => false,
        ]);

        $this->actingAs($user)->userObj->updateUserNotifications($updateData);
        $updateData->user_id = $user->user_id;
        $this->assertDatabaseHas('user_settings', $updateData->toArray());
    }

    //  Test updating the users password as installer - do not force change
    public function test_update_user_password()
    {
        $user = $this->getTech();
        $newPass = 'myNewPassword';
        $this->actingAs($this->getInstaller())->userObj->updateUserPassword($newPass, $user->user_id);
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'password_expires' => Carbon::now()->addDays(config('auth.passwords.settings.expire')),
        ]);
    }

    //  Test updating the users password as installer - force change
    public function test_update_user_password_force_change()
    {
        $user = $this->getTech();
        $newPass = 'myNewPassword';
        $this->actingAs($this->getInstaller())->userObj->updateUserPassword($newPass, $user->user_id, true);
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'password_expires' => Carbon::now()->subDay(),
        ]);
    }

    //  Test updating the users own password
    // public function test_update_user_password_own_password()
    // {
    //     $user    = $this->getTech();
    //     $newPass = 'myNewPassword';
    //     $this->actingAs($user)->userObj->updateUserPassword($newPass);
    //     $this->assertDatabaseHas('users', [
    //         'user_id' => $user->user_id,
    //         //  TODO - trim timestamp so it shows properly for comparison
    //         'password_expires' => Carbon::now()->addDays(config('auth.passwords.settings.expire')),
    //     ]);
    // }
}
