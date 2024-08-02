<?php

namespace Tests\Feature\Maintenance;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class ViewLogTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $filename = 'TechBench-'.$date;

        $response = $this->get(route('maint.logs.view', [
            'Application',
            $filename,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $filename = 'TechBench-'.$date;

        $response = $this->actingAs(User::factory()->create())
            ->get(route('maint.logs.view', ['Application', $filename]));
        $response->assertStatus(403);
    }

    public function test_invoke_bad_channel()
    {
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $filename = 'TechBench-'.$date;

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('maint.logs.view', ['YourMom', $filename]));
        $response->assertStatus(404);
    }

    public function test_invoke_bad_file_name()
    {
        $date = date('Y-m-d', strtotime(Carbon::now()->addDays(30)));
        $filename = 'TechBench-'.$date;

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('maint.logs.view', ['Application', 'yourmom.com']));
        $response->assertStatus(404);
    }

    public function test_invoke()
    {
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $filename = 'TechBench-'.$date;

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('maint.logs.view', ['daily', $filename]));
        $response->assertSuccessful();
    }
}
