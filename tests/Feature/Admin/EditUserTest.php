<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    protected $user, $installer;

    public function setUp():void
    {
        Parent::setUp();

        $this->user      = $this->getTech();
        $this->installer = $this->getInstaller();
    }

    public function test_visit_user_index_page_as_guest()
    {
        $response = $this->get(route('admin.user.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_user_index_page_no_permission()
    {
        $response = $this->actingAs($this->user)->get(route('admin.user.index'));

        $response->assertStatus(403);
    }

    public function test_visit_user_index_page()
    {
        $response = $this->actingAs($this->installer)->get(route('admin.user.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userIndex');
    }

    public function test_visit_edit_user_page_as_guest()
    {
        $response = $this->get(route('admin.user.edit', $this->user->user_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_edit_user_page_no_permissions()
    {
        $response = $this->actingAs($this->user)->get(route('admin.user.edit', $this->user->user_id));

        $response->assertStatus(403);
    }

    public function test_visit_new_user_page()
    {
        $response = $this->actingAs($this->installer)->get(route('admin.user.edit', $this->user->user_id));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userEdit');
    }

    public function test_visit_edit_user_page_user_that_does_not_exist()
    {
        $response = $this->actingAs($this->installer)->get(route('admin.user.edit', 5150));

        // dd($response->getContent());

        $response->assertStatus(404);
    }

    public function test_visit_edit_user_page_user_with_higher_permissions()
    {
        $user = factory(User::class)->create(['role_id' => 2]);

        $response = $this->actingAs($user)->get(route('admin.user.edit', $this->installer->user_id));

        $response->assertStatus(403);
    }

    public function test_access_user_and_email_verification_with_existing_username()
    {
        $response = $this->actingAs($this->installer)->get(route('admin.checkUser', [$this->user->username, 'username']));

        $response->assertSuccessful();
        $response->assertJson([
            'duplicate' => true,
            'user'      => $this->user->full_name,
            'active'    => 1,
        ]);
    }

    public function test_access_user_and_email_verification_with_existing_email()
    {
        $response = $this->actingAs($this->installer)->get(route('admin.checkUser', [$this->user->email, 'email']));

        $response->assertSuccessful();
        $response->assertJson([
            'duplicate' => true,
            'user'      => $this->user->full_name,
            'active'    => 1,
        ]);
    }

    public function test_access_user_and_email_verification()
    {
        $newUser = factory(User::class)->make();
        $response = $this->actingAs($this->installer)->get(route('admin.checkUser', [$newUser->username, 'username']));

        $response->assertSuccessful();
        $response->assertJson([
            'duplicate' => false,
        ]);
    }

    public function test_edit_user_as_guest()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_user_no_permissions()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->user)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertStatus(403);
    }

    public function test_edit_user()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_edit_user_that_does_not_exist()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', 5150), $data);

        $response->assertStatus(404);
    }

    public function test_edit_user_with_higher_permissions()
    {
        $user = factory(User::class)->create(['role_id' => 2]);
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($user)->put(route('admin.user.update', $this->installer->user_id), $data);

        $response->assertStatus(403);
    }

    public function test_edit_user_no_change_to_username_or_email()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $this->user->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $this->user->email,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_edit_user_role_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('role');
    }

    public function test_edit_user_username_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('username');
    }

    public function test_edit_user_username_validation_error_duplicate_username()
    {
        $newData = factory(User::class)->make();
        $otherUser = factory(User::class)->create();
        $data = [
            'role'       => 4,
            'username'   => $otherUser->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('username');
    }

    public function test_edit_user_first_name_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('first_name');
    }

    public function test_edit_user_last_name_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('last_name');
    }

    public function test_edit_user_email_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_create_edit_email_validation_error_duplicate_email()
    {
        $newData = factory(User::class)->make();
        $otherUser = factory(User::class)->create();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $otherUser->email,
        ];

        $response = $this->actingAs($this->installer)->put(route('admin.user.update', $this->user->user_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_change_password_for_user_as_guest()
    {
        $data = [
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
            'user_id'               => $this->user->user_id,
            'force_change'          => 1,
        ];
        $response = $this->post(route('admin.user.changePassword'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_change_password_for_user_no_permission()
    {
        $data = [
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
            'user_id'               => $this->user->user_id,
            'force_change'          => 1,
        ];
        $response = $this->actingAs($this->user)->post(route('admin.user.changePassword'), $data);

        $response->assertStatus(403);
    }

    public function test_change_password_for_user()
    {
        $data = [
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
            'user_id'               => $this->user->user_id,
            'force_change'          => 1,
        ];
        $response = $this->actingAs($this->installer)->post(route('admin.user.changePassword'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_change_password_for_user_no_need_to_force()
    {
        $data = [
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
            'user_id'               => $this->user->user_id,
            'force_change'          => 0,
        ];
        $response = $this->actingAs($this->installer)->post(route('admin.user.changePassword'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_change_password_for_user_password_validation_error()
    {
        $data = [
            'password'              => 'newPassword',
            'password_confirmation' => 'newPasswordThatDoesNotMatch',
            'user_id'               => $this->user->user_id,
            'force_change'          => 1,
        ];
        $response = $this->actingAs($this->installer)->post(route('admin.user.changePassword'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_change_password_for_user_user_id_validation_error()
    {
        $data = [
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
            'force_change'          => 1,
        ];
        $response = $this->actingAs($this->installer)->post(route('admin.user.changePassword'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['user_id']);
    }

    public function test_change_password_for_user_bad_user_id()
    {
        $data = [
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
            'user_id'               => 456876544,
            'force_change'          => 1,
        ];
        $response = $this->actingAs($this->installer)->post(route('admin.user.changePassword'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => false]);
    }

    public function test_change_password_for_user_with_less_permissions()
    {
        $user = factory(User::class)->create(['role_id' => 2]);
        $data = [
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
            'user_id'               => $this->installer->user_id,
            'force_change'          => 1,
        ];
        $response = $this->actingAs($user)->post(route('admin.user.changePassword'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => false]);
    }

    public function test_deactivate_user_as_guest()
    {
        $response = $this->delete(route('admin.user.destroy', $this->user->user_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_deactivate_user_no_permission()
    {
        $response = $this->actingAs($this->user)->delete(route('admin.user.destroy', $this->user->user_id));

        $response->assertStatus(403);
    }

    public function test_deactivate_user()
    {
        $response = $this->actingAs($this->installer)->delete(route('admin.user.destroy', $this->user->user_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_deactivate_yourself()
    {
        $response = $this->actingAs($this->installer)->delete(route('admin.user.destroy', $this->installer->user_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => false]);
    }

    public function test_deactivate_user_with_higher_permission()
    {
        $user = factory(User::class)->create(['role_id' => 2]);
        $response = $this->actingAs($user)->delete(route('admin.user.destroy', $this->installer->user_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => false]);
    }

    public function test_deactivate_user_that_does_not_exist()
    {
        $response = $this->actingAs($this->installer)->delete(route('admin.user.destroy', 5150));

        $response->assertSuccessful();
        $response->assertJson(['success' => false]);
    }
}
