<?php

namespace Tests\Feature\TechTip;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DownloadTipTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.download', $tip->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.download', $tip->slug));

        $response->assertSuccessful();
    }
}
