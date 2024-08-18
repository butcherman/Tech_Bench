<?php

namespace Tests\Feature\FileLink;

use App\Models\FileLink;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExtendLinkTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $link = FileLink::factory()->create();

        $response = $this->get(route('links.extend', $link->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_feature_disabled()
    {
        config(['fileLink.feature_enabled' => false]);
        $user = User::factory()->create();
        $link = FileLink::factory()->create();

        $response = $this->actingAs($user)->get(route('links.extend', $link->link_id));
        $response->assertStatus(403);
    }

    public function test_invoke_different_user()
    {
        config(['fileLink.feature_enabled' => true]);
        $user = User::factory()->create();
        $link = FileLink::factory()->create();

        $response = $this->actingAs($user)->get(route('links.extend', $link->link_id));
        $response->assertStatus(403);
    }

    public function test_invoke_no_permission()
    {
        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)->get(route('links.extend', $link->link_id));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        config(['fileLink.feature_enabled' => true]);
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)->get(route('links.extend', $link->link_id));
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('file_links', [
            'link_id' => $link->link_id,
            'expire' => Carbon::parse($link->expire)->addDays(30)->format('Y-m-d'),
        ]);
    }
}
