<?php

namespace Tests\Feature\Report\Customer;

use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;

class CustomerFilesReportTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Customer::factory()->count(20)->create();
    }

    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('reports.customer.files'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('reports.customer.files'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 2]))
            ->get(route('reports.customer.files'));
        $response->assertSuccessful();
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $data = [
            'hasInput' => 'has',
            'fileTypes' => [1, 2],
        ];

        $response = $this->put(route('reports.customer.run-files'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $data = [
            'hasInput' => 'has',
            'fileTypes' => [1, 2],
        ];

        $response = $this->ActingAs(User::factory()->createQuietly())
            ->put(route('reports.customer.run-files'), $data);
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $data = [
            'hasInput' => 'has',
            'fileTypes' => [1, 2],
        ];

        $response = $this->ActingAs(User::factory()->createQuietly(['role_id' => 2]))
            ->put(route('reports.customer.run-files'), $data);
        $response->assertSuccessful();
    }

    public function test_show_missing()
    {
        $data = [
            'hasInput' => 'doesnt have',
            'fileTypes' => [1, 2],
        ];

        $response = $this->ActingAs(User::factory()->createQuietly(['role_id' => 2]))
            ->put(route('reports.customer.run-files'), $data);
        $response->assertSuccessful();
    }
}
