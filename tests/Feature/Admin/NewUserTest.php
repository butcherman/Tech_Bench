<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Notification;

class NewUserTest extends TestCase
{
    protected $user, $installer;

    public function setUp():void
    {
        Parent::setUp();

        $this->user      = $this->getTech();
        $this->installer = $this->getInstaller();
    }

    public function test_visit_new_user_page_as_guest()
    {
        $response = $this->get(route('admin.user.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_new_user_page_no_permissions()
    {
        $response = $this->actingAs($this->user)->get(route('admin.user.create'));

        $response->assertStatus(403);
    }

    public function test_visit_new_user_page()
    {
        $response = $this->actingAs($this->installer)->get(route('admin.user.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.newUser');
    }

    public function test_access_user_and_email_verification_as_guest()
    {
        $response = $this->get(route('admin.checkUser', [$this->user->username, 'username']));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_access_user_and_email_verification_no_permissions()
    {
        $response = $this->actingAs($this->user)->get(route('admin.checkUser', [$this->user->username, 'username']));

        $response->assertStatus(403);
    }

    public function test_access_user_and_email_verification_with_duplicate_username()
    {
        $response = $this->actingAs($this->installer)->get(route('admin.checkUser', [$this->user->username, 'username']));

        $response->assertSuccessful();
        $response->assertJson([
            'duplicate' => true,
            'user'      => $this->user->full_name,
            'active'    => 1,
        ]);
    }

    public function test_access_user_and_email_verification_with_duplicate_email()
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

    public function test_create_new_user_as_guest()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->post(route('admin.user.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_new_user_no_permissions()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->user)->post(route('admin.user.store'), $data);

        $response->assertStatus(403);
    }

    public function test_create_new_user()
    {
        Notification::fake();

        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->post(route('admin.user.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_create_new_user_role_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->post(route('admin.user.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('role');
    }

    public function test_create_new_user_username_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->post(route('admin.user.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('username');
    }

    public function test_create_new_user_username_validation_error_duplicate_username()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $this->user->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->post(route('admin.user.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('username');
    }

    public function test_create_new_user_first_name_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->post(route('admin.user.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('first_name');
    }

    public function test_create_new_user_last_name_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->installer)->post(route('admin.user.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('last_name');
    }

    public function test_create_new_user_email_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
        ];

        $response = $this->actingAs($this->installer)->post(route('admin.user.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_create_new_user_email_validation_error_duplicate_email()
    {
        $newData = factory(User::class)->make();
        $data = [
            'role'       => 4,
            'username'   => $newData->username,
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $this->user->email,
        ];

        $response = $this->actingAs($this->installer)->post(route('admin.user.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }
}
