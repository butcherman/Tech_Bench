<?php

namespace Tests\Feature\Admin;

use App\Actions\Admin\SendTestEmail;
use App\Models\User;
use Mockery\MockInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class SendTestEmailControllerTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->put(route('admin.test-email'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->put(route('admin.test-email'));

        $response->assertStatus(403);
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = $user = User::factory()->createQuietly(['role_id' => 1]);

        $data = [
            'host' => 'smtp.example.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'test@example.com',
            'password' => 'secret',
            'from_address' => 'test@example.com',
            'require_auth' => true,
            'recipient' => 'billybob@em.com',
        ];

        $this->mock(SendTestEmail::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once();
        });

        $response = $this->actingAs($user)
            ->put(route('admin.test-email'), $data);

        $response->assertStatus(302)
            ->assertSessionHas([
                'success' => 'Test email accepted by the mail server.',
            ]);
    }

    public function test_invoke_message_fails(): void
    {
        /** @var User $user */
        $user = $user = User::factory()->createQuietly(['role_id' => 1]);

        $data = [
            'host' => 'smtp.example.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'test@example.com',
            'password' => 'secret',
            'from_address' => 'test@example.com',
            'require_auth' => true,
            'recipient' => 'billybob@em.com',
        ];

        $this->mock(SendTestEmail::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once()->andThrow(TransportException::class);
        });

        $response = $this->actingAs($user)
            ->put(route('admin.test-email'), $data);

        $response->assertStatus(302)
            ->assertSessionHasErrors('mail_settings');
    }
}
