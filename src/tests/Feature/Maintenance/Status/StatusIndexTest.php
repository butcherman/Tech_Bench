<?php

namespace Tests\Feature\Maintenance\Status;

use App\Models\User;
use App\Services\Maintenance\DockerControlService;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery\MockInterface;
use Tests\TestCase;

class StatusIndexTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('maint.status.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('maint.status.index'));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $this->mock(
            DockerControlService::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getDockerStatus')
                    ->once()
                    ->andReturn([]);
            });

        $response = $this->actingAs($user)->get(route('maint.status.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Maint/Status/Index')
                ->has('summary')
            );
    }
}
