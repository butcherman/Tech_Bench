<?php

namespace Tests\Feature\Admin\Config;

use App\Mail\Admin\TestEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class SendTestEmailTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('admin.test-email'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.test-email'));

        $response->assertStatus(403);
    }

    public function test_invoke(): void
    {
        Mail::fake();

        /** @var User $user */
        $user = $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.test-email'));

        $response->assertStatus(302);

        Mail::assertSent(TestEmail::class);
    }

    public function test_invoke_message_fails(): void
    {
        /** @var User $user */
        $user = $user = User::factory()->createQuietly(['role_id' => 1]);

        Mail::shouldReceive('to')
            ->once()
            ->with($user)
            ->andThrow(new TransportException('Test Exception'));

        $response = $this->actingAs($user)
            ->get(route('admin.test-email'));

        $response->assertStatus(302)
            ->assertSessionHasErrors('email', 'Test Exception');
    }
}
