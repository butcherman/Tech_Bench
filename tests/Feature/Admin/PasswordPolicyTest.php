<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Settings;

class PasswordPolicyTest extends TestCase
{
    //  Try to visit the password policy page as a guest
    public function test_visit_password_policy_page_as_guest()
    {
        $response = $this->get(route('admin.passwordPolicy'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to visit the password policy page logged in without permission
    public function test_visit_password_policy_page_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.passwordPolicy'));

        $response->assertStatus(403);
    }

    //  Try to visit the password policy page logged in as a basic admin with Manage users access
    public function test_visit_password_policy_page_basic_permission()
    {
        $response = $this->actingAs($this->UserWithPermission('Manage Users'))->get(route('admin.passwordPolicy'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userSecurity');
    }

    //  Try to visit the password policy page as an installer
    public function test_visit_password_policy_page()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.passwordPolicy'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userSecurity');
    }

    //  Try to submit the password policy page as a guest
    public function test_submit_password_policy_page_as_guest()
    {
        $data = [
            'passExpire' => 30,
        ];
        $response = $this->post(route('admin.passwordPolicy'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to submit the password policy page logged in without permission
    public function test_submit_password_policy_page_no_permission()
    {
        $data = [
            'passExpire' => 30,
        ];
        $response = $this->actingAs($this->getTech())->post(route('admin.passwordPolicy'), $data);

        $response->assertStatus(403);
    }

    //  Try to submit the password policy page logged in as a basic admin with Manage users access
    public function test_submit_password_policy_page_basic_permission()
    {
        $data = [
            'passExpire' => 30,
        ];
        $response = $this->actingAs($this->UserWithPermission('Manage Users'))->post(route('admin.passwordPolicy'), $data);

        $response->assertStatus(302);
        $response->assertSessionHas(["success" => "User Security Updated"]);

    }

    //  Try to submit the password policy page as an installer
    public function test_submit_password_policy_page()
    {
        //  Before running test, change the current password policy to 0 days to test updating users
        Settings::firstOrCreate(
            ['key'   => 'auth.passwords.settings.expire'],
            ['key'   => 'auth.passwords.settings.expire', 'value' => 0]
        )->update(['value' => 0]);

        $data = [
            'passExpire' => 30,
        ];
        $response = $this->actingAs($this->getInstaller())->post(route('admin.passwordPolicy'), $data);

        $response->assertStatus(302);
        $response->assertSessionHas(["success" => "User Security Updated"]);
    }

    //  Try to submit the password policy page as an installer and zero out password policy
    public function test_submit_password_policy_page_zero_out()
    {
        //  Before running test, change the current password policy to 0 days to test updating users
        Settings::firstOrCreate(
            ['key'   => 'auth.passwords.settings.expire'],
            ['key'   => 'auth.passwords.settings.expire', 'value' => 90]
        )->update(['value' => 90]);

        $data = [
            'passExpire' => 0,
        ];
        $response = $this->actingAs($this->getInstaller())->post(route('admin.passwordPolicy'), $data);

        $response->assertStatus(302);
        $response->assertSessionHas(["success" => "User Security Updated"]);
    }
}
