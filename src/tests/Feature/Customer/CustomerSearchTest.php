<?php

namespace Tests\Feature\Customer;

use App\Actions\Customer\CustomerSearch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class CustomerSearchTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */

    public function test_search_guest(): void
    {
        $searchData = [
            'basic' => true,
            'perPage' => 25,
        ];

        $response = $this->post(route('customers.search'), $searchData);
        $response->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_search_all_customers(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $searchData = [
            'basic' => false,
            'perPage' => 25,
            'searchFor' => null,
            'page' => 1,
        ];

        $this->mock(CustomerSearch::class, function (MockInterface $mock) {
            $mock->shouldReceive('__invoke')->once();
        });

        $response = $this->actingAs($user)
            ->post(route('customers.search'), $searchData);

        $response->assertSuccessful();
    }
}
