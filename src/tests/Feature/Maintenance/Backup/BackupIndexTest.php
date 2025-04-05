<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BackupIndexTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('maint.backups.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('maint.backups.index'));
        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.backups.index'));

        $response->assertSuccessful()
            ->assertInertia(fn(Assert $page) => $page
                ->component('Maint/Backup')
                ->has('backup-list'));
    }
}
