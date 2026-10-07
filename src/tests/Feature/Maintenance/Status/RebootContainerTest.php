<?php

namespace Tests\Feature\Maintenance\Status;

use App\Enums\ContainerList;
use App\Models\User;
use App\Services\Maintenance\DockerControlService;
use Mockery\MockInterface;
use Tests\TestCase;

class RebootContainerTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $container = ContainerList::Nginx;

        $response = $this->post(route('maint.status.reboot', $container->value));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $container = ContainerList::Nginx;

        $response = $this->actingAs($user)
            ->post(route('maint.status.reboot', $container->value));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $container = ContainerList::Nginx;

        $this->mock(
            DockerControlService::class,
            function (MockInterface $mock) use ($container) {
                $mock->shouldReceive('rebootContainer')
                    ->once()
                    ->with($container)
                    ->andReturnNull();
            });

        $response = $this->actingAs($user)
            ->post(route('maint.status.reboot', $container->value));

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Service Rebooting');
    }

    public function test_invoke_bad_container(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $container = 'random';

        $response = $this->actingAs($user)
            ->post(route('maint.status.reboot', $container));

        $response->assertNotFound();
    }
}
