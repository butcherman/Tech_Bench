<?php

namespace Tests\Feature\Reports;

use App\Exceptions\Misc\ReportDataExpiredException;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RunReportTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_put_guest(): void
    {
        $data = [
            'all_customers' => true,
            'customer_list' => [],
        ];

        $response = $this->put(route('reports.run', [
            'customers',
            'customer-summary-report',
        ]), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_get_guest(): void
    {
        $data = [
            'all_customers' => true,
            'customer_list' => [],
        ];

        $response = $this->withSession(['params' => $data])
            ->get(route('reports.run', [
                'customers',
                'customer-summary-report',
            ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_put_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $data = [
            'all_customers' => true,
            'customer_list' => [],
        ];

        $response = $this->actingAs($user)
            ->put(route('reports.run', [
                'customers',
                'customer-summary-report',
            ]), $data);

        $response->assertForbidden();
    }

    public function test_invoke_get_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $data = [
            'all_customers' => true,
            'customer_list' => [],
        ];

        $response = $this->actingAs($user)
            ->withSession(['params' => $data])
            ->get(route('reports.run', [
                'customers',
                'customer-summary-report',
            ]));

        $response->assertForbidden();
    }

    public function test_invoke_put(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);
        $data = [
            'all_customers' => true,
            'customer_list' => [],
        ];

        $response = $this->actingAs($user)
            ->put(route('reports.run', [
                'customers',
                'customer-summary-report',
            ]), $data);

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Report/Run')
            );
    }

    public function test_invoke_get(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);
        $data = [
            'all_customers' => true,
            'customer_list' => [],
        ];

        $response = $this->actingAs($user)
            ->withSession(['params' => collect($data)])
            ->get(route('reports.run', [
                'customers',
                'customer-summary-report',
            ]));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Report/Run')
            );
    }

    public function test_invoke_get_missing_params(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);

        $this->expectException(ReportDataExpiredException::class);

        $response = $this->actingAs($user)
            ->withoutExceptionHandling()
            ->get(route('reports.run', [
                'customers',
                'customer-summary-report',
            ]));

        $response->assertStatus(302)
            ->assertSessionHasErrors(['page_expired']);

        Exceptions::assertReported(ReportDataExpiredException::class);
    }

    public function test_invoke_invalid_report(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);
        $data = [
            'all_customers' => true,
            'customer_list' => [],
        ];

        $response = $this->actingAs($user)
            ->withSession(['params' => collect($data)])
            ->get(route('reports.run', [
                'invalid',
                'wrong-data',
            ]));

        $response->assertStatus(404);
    }
}
