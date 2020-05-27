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

    // public function test_create_user_no_permission()
    // {
        //  FIXME - add middleware to this route
    //     $response = $this->actingAs($this->getTech())->get(route('admin.user.create'));
    //     $response->assertStatus(403);
    // }

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
}
