<?php

namespace Tests\Feature\TechTip\Public;

use App\Exceptions\TechTip\PublicTipsDisabledException;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
use Tests\TestCase;

class SearchPublicTipTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_search_guest(): void
    {
        config(['tech-tips.allow_public' => true]);

        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $response = $this->post(route('publicTips.search', $searchData));

        $response->assertSuccessful();
        $this->assertGuest();
    }

    public function test_search(): void
    {
        config(['tech-tips.allow_public' => true]);

        TechTip::factory()->count(5)->create(['public' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $response = $this->actingAs($user)
            ->post(route('publicTips.search', $searchData));

        $response->assertSuccessful();
    }

    public function test_search_feature_disabled(): void
    {
        Exceptions::fake();

        config(['tech-tips.allow_public' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $searchData = [
            'perPage' => 25,
            'page' => 1,
        ];

        $this->expectException(PublicTipsDisabledException::class);

        $response = $this->actingAs($user)
            ->withoutExceptionHandling()
            ->post(route('publicTips.search', $searchData));

        $response->assertStatus(404);

        Exceptions::assertReported(PublicTipsDisabledException::class);
    }
}
