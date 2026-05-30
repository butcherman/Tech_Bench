<?php

namespace Tests\Feature\TechTip;

use App\Exceptions\TechTip\DownloadTipNotAllowedException;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
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

    public function test_invoke_feature_disabled(): void
    {
        Exceptions::fake();

        config(['tech-tips.allow_download' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(DownloadTipNotAllowedException::class);

        $response = $this->actingAs($user)
            ->get(route('tech-tips.download', $tip->slug));

        $response->assertStatus(404);

        Exceptions::assertReported(DownloadTipNotAllowedException::class);
    }
}
