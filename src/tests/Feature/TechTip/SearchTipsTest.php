<?php

namespace Tests\Feature\TechTip;

use App\Models\User;
use Tests\TestCase;

class SearchTipsTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest()
    {
        $searchData = [
            'searchFor' => null,
            'typeList' => [],
            'equipList' => [],
            'page' => 1,
            'perPage' => 25,
        ];

        $response = $this->post(route('tech-tips.search', $searchData));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_search_all_tips()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $searchData = [
            'searchFor' => null,
            'typeList' => [],
            'equipList' => [],
            'page' => 1,
            'perPage' => 25,
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.search', $searchData));

        $response->assertSuccessful();
    }
}
