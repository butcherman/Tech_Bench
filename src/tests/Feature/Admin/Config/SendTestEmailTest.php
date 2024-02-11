<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use App\Notifications\Admin\SendTestEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendTestEmailTest extends TestCase
{
    public function test_filler()
    {
        $this->assertTrue(true);
    }
    /**
     * Invoke Method
     */
    // public function test_invoke_guest()
    // {
    //     $response = $this->get(route('admin.email.test'));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_invoke_no_permission()
    // {
    //     $response = $this->actingAs(User::factory()->create())
    //         ->get(route('admin.email.test'));
    //     $response->assertStatus(403);
    // }

    // public function test_invoke()
    // {
    //     Notification::fake();

    //     $response = $this->actingAs($user = User::factory()->create(['role_id' => 1]))
    //         ->get(route('admin.email.test'));
    //     $response->assertStatus(302);

    //     Notification::assertSentTo($user, SendTestEmail::class);
    // }
}
