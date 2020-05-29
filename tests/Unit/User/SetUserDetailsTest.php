<?php

namespace Tests\Unit\User;

use Carbon\Carbon;
use Tests\TestCase;

use Illuminate\Support\Facades\Notification;

use App\Domains\User\SetUserDetails;
use App\Http\Requests\Admin\NewUserRequest;

use App\User;

use App\Http\Requests\User\UserAccountRequest;
use App\Http\Requests\User\UserSettingsRequest;

class SetUserDetailsTest extends TestCase
{
    protected $testObj, $testUser;

    public function setUp():void
    {
        Parent::setup();

        $this->testUser = factory(User::class)->create()->makeVisible(['user_id', 'role_id', 'username'])->makeHidden('full_name', 'initials');
        $this->testObj  = new SetUserDetails;
    }

    public function test_create_user()
    {
        Notification::fake();

        $data = factory(User::class)->make()->makeVisible('username');
        $data = new NewUserRequest($data->toArray());

        $res = $this->testObj->createUser($data);
        $this->assertTrue($res > 0);
        $this->assertDatabaseHas('users', ['user_id' => $res, 'username' => $data->username]);

        //  TODO - Assert event triggered and email sent
    }

    public function test_update_user()
    {
        $newData = factory(User::class)->make();
        $userData = new UserAccountRequest([
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ]);

        $res = $this->actingAs($this->testUser)->testObj->updateUser($userData, $this->testUser->user_id);

        $this->assertTrue($res);
        $this->assertDatabaseHas('users', $userData->toArray());
    }

    public function test_update_settings()
    {
        $updateData = new UserSettingsRequest([
            'em_tech_tip'     => false,
            'em_file_link'    => false,
            'em_notification' => false,
            'auto_del_link'   => false,
        ]);

        $this->actingAs($this->getInstaller())->testObj->updateSettings($updateData, $this->testUser->user_id);
        $updateData->user_id = $this->testUser->user_id;
        $this->assertDatabaseHas('user_settings', $updateData->toArray());
    }

    public function test_update_password()
    {
        $newPass = 'SuperSecurePassword';
        $res = $this->actingAs($this->testUser)->testObj->updatePassword($newPass, $this->testUser->user_id);

        $this->assertTrue($res);
        //  FIXME - verify database password changed properly
        // $this->assertDatabaseHas('users', [
        //     'user_id'          => $this->testUser->user_id,
        //     'password'         => bcrypt($newPass),
        // ]);
    }

    public function test_update_password_force_change()
    {
        $newPass = 'SuperSecurePassword';
        $res = $this->actingAs($this->testUser)->testObj->updatePassword($newPass, $this->testUser->user_id, true);

        $this->assertTrue($res);
        //  FIXME - verify database password changed properly
        // $this->assertDatabaseHas('users', [
        //     'user_id'          => $this->testUser->user_id,
        //     'password'         => bcrypt($newPass),
        // ]);
    }

    public function test_disable_user()
    {
        $res = $this->testObj->disableUser($this->testUser->user_id);

        $this->assertTrue($res);
        $this->assertSoftDeleted('users', $this->testUser->toArray());
    }

    public function test_activate()
    {
        $user = factory(User::class)->create([
            'deleted_at' => NOW(),
        ]);

        $res = $this->testObj->reactivateUser($user->user_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('users', ['user_id' => $user->user_id, 'deleted_at' => null]);
    }
}
