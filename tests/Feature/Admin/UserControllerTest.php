<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_check_user_guest()
    {
        $testUser = factory(User::class)->create();

        $response = $this->get(route('admin.user.check', [$testUser->username, 'username']));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_check_user_fail()
    {
        $testUser = factory(User::class)->create();

        $response1 = $this->actingAs($this->getTech())->get(route('admin.user.check', [$testUser->username, 'username']));
        $response1->assertSuccessful();
        $response1->assertJson([
            'duplicate' => true,
            'user'      => $testUser->full_name,
            'username'  => $testUser->username,
            'active'    => true,
        ]);

        $response2 = $this->actingAs($this->getTech())->get(route('admin.user.check', [$testUser->email, 'email']));
        $response2->assertSuccessful();
        $response2->assertJson([
            'duplicate' => true,
            'user'      => $testUser->full_name,
            'username'  => $testUser->username,
            'active'    => true,
        ]);
    }

    public function test_check_user_pass()
    {
        $testUser = factory(User::class)->make();

        $response1 = $this->actingAs($this->getTech())->get(route('admin.user.check', [$testUser->username, 'username']));
        $response1->assertSuccessful();
        $response1->assertJson([
            'duplicate' => false,
        ]);

        $response2 = $this->actingAs($this->getTech())->get(route('admin.user.check', [$testUser->email, 'email']));
        $response2->assertSuccessful();
        $response2->assertJson([
            'duplicate' => false,
        ]);
    }

    public function test_create_guest()
    {
        $response = $this->get(route('admin.user.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_user_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.user.create'));
        $response->assertStatus(403);
    }

    public function test_create_user()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.user.create'));
        $response->assertSuccessful();
        $response->assertViewIs('admin.newUser');
    }

    public function test_store_guest()
    {
        $user = factory(User::class)->make();

        $response = $this->post(route('admin.user.store'), $user->toArray());
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_user_no_permission()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($this->getTech())->post(route('admin.user.store'), $user->toArray());
        $response->assertStatus(403);
    }

    public function test_store_user()
    {
        Notification::fake();

        $user = factory(User::class)->make()->makeVisible(['username', 'role_id']);

        $response = $this->actingAs($this->getInstaller())->post(route('admin.user.store'), $user->toArray());
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_list_active_guest()
    {
        factory(User::class, 10)->create();
        $response = $this->get(route('admin.user.active_users'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_list_active_no_permission()
    {
        factory(User::class, 10)->create();
        $response = $this->actingAs($this->getTech())->get(route('admin.user.active_users'));

        $response->assertStatus(403);
    }

    public function test_list_active()
    {
        factory(User::class, 10)->create();
        $response = $this->actingAs($this->getInstaller())->get(route('admin.user.active_users'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userList');
    }





    public function test_list_inactive_guest()
    {
        factory(User::class, 10)->create(['deleted_at' => NOW()]);
        $response = $this->get(route('admin.user.inactive_users'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_list_inactive_no_permission()
    {
        factory(User::class, 10)->create(['deleted_at' => NOW()]);
        $response = $this->actingAs($this->getTech())->get(route('admin.user.inactive_users'));

        $response->assertStatus(403);
    }

    public function test_list_inactive()
    {
        factory(User::class, 10)->create(['deleted_at' => NOW()]);
        $response = $this->actingAs($this->getInstaller())->get(route('admin.user.inactive_users'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userList');
    }











    public function test_edit_guest()
    {
        $response = $this->get(route('admin.user.edit', $this->getTech()->user_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.user.edit', $this->getTech()->user_id));
        $response->assertStatus(403);
    }

    public function test_edit_mismatch_permission()
    {
        $response = $this->actingAs($this->getUserWithPermission('Manage Users'))->get(route('admin.user.edit', $this->getInstaller()->user_id));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.user.edit', $this->getTech()->user_id));
        $response->assertSuccessful();
        $response->assertViewIs('admin.userEdit');
    }

    public function test_update_guest()
    {
        $user = $this->getTech();
        $newData = factory(User::class)->make();
        $data = [
            'user_id'    => $user->user_id,
            'role_id'    => $user->role_id,
            'username'   => $newData->username,
            'email'      => $newData->email,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
        ];

        $response = $this->post(route('admin.user.update', $user->user_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $user = $this->getTech();
        $newData = factory(User::class)->make();
        $data = [
            'user_id'    => $user->user_id,
            'role_id'    => $user->role_id,
            'username'   => $newData->username,
            'email'      => $newData->email,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
        ];

        $response = $this->actingAs($this->getTech())->post(route('admin.user.update', $user->user_id), $data);
        $response->assertStatus(403);
    }

    public function test_update_mismatch_permission()
    {
        $user = $this->getInstaller();
        $newData = factory(User::class)->make();
        $data = [
            'user_id'    => $user->user_id,
            'role_id'    => $user->role_id,
            'username'   => $newData->username,
            'email'      => $newData->email,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
        ];

        $response = $this->actingAs($this->getUserWithPermission('Manage Users'))->post(route('admin.user.update', $user->user_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $user = $this->getTech();
        $newData = factory(User::class)->make();
        $data = [
            'user_id'    => $user->user_id,
            'role_id'    => $user->role_id,
            'username'   => $newData->username,
            'email'      => $newData->email,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
        ];

        $response = $this->actingAs($this->getInstaller())->post(route('admin.user.update', $user->user_id), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_change_password_guest()
    {
        $user = $this->getTech();
        $data = [
            'password'              => 'NewCoolPassword',
            'password_confirmation' => 'NewCoolPassword',
            'user_id'               => $user->user_id,
        ];

        $response = $this->post(route('admin.user.change_password'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_change_password_no_permission()
    {
        $user = $this->getTech();
        $data = [
            'password'              => 'NewCoolPassword',
            'password_confirmation' => 'NewCoolPassword',
            'user_id'               => $user->user_id,
        ];

        $response = $this->actingAs($this->getTech())->post(route('admin.user.change_password'), $data);
        $response->assertStatus(403);
    }

    public function test_change_password_mismatch_permission()
    {
        $user = $this->getInstaller();
        $data = [
            'password'              => 'NewCoolPassword',
            'password_confirmation' => 'NewCoolPassword',
            'user_id'               => $user->user_id,
        ];

        $response = $this->actingAs($this->getUserWithPermission('Manage Users'))->post(route('admin.user.change_password'), $data);
        $response->assertStatus(403);
    }

    public function test_change_password()
    {
        $user = $this->getTech()->makeVisible('role_id');
        $data = [
            'password'              => 'NewCoolPassword',
            'password_confirmation' => 'NewCoolPassword',
            'user_id'               => $user->user_id,
        ];

        $response = $this->actingAs($this->getInstaller())->post(route('admin.user.change_password'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_login_history()
    {
        //  TODO - this function currently does nothing
        $res = $this->actingAs($this->getInstaller())->get(route('admin.user.login_history', [1, 'yer+mom']));
        $res->assertSuccessful();
    }

    public function test_destory_guest()
    {
        $response = $this->delete(route('admin.user.destroy', $this->getTech()->user_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destory_no_permission()
    {
        $response = $this->actingAs($this->getTech())->delete(route('admin.user.destroy', $this->getTech()->user_id));
        $response->assertStatus(403);
    }

    public function test_destory_mismatch_permission()
    {
        $response = $this->actingAs($this->getUserWithPermission('Manage Users'))->delete(route('admin.user.destroy', $this->getInstaller()->user_id));
        $response->assertStatus(403);
    }

    public function test_destory()
    {
        $response = $this->actingAs($this->getInstaller())->delete(route('admin.user.destroy', $this->getTech()->user_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_activate_guest()
    {
        $user = factory(User::class)->create([
            'deleted_at' => NOW(),
        ]);

        $response = $this->get(route('admin.user.activate', $user->user_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_activate_no_permission()
    {
        $user = factory(User::class)->create([
            'deleted_at' => NOW(),
        ]);

        $response = $this->actingAs($this->getTech())->get(route('admin.user.activate', $user->user_id));
        $response->assertStatus(403);
    }

    public function test_activate()
    {
        $user = factory(User::class)->create([
            'deleted_at' => NOW(),
        ]);

        $response = $this->actingAs($this->getInstaller())->get(route('admin.user.activate', $user->user_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
