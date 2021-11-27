<?php

namespace Tests\Feature\Admin\Logs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
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

        $response = $this->get(route('admin.logs.view', ['Application', $filename]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $filename = 'TechBench-'.$date;

        $response = $this->actingAs(User::factory()->create())->get(route('admin.logs.view', ['Application', $filename]));
        $response->assertStatus(403);
    }

    //  TODO - Fix so that this can pull an acutal file
    // public function test_invoke()
    // {
    //     $date = date('Y-m-d', strtotime(Carbon::now()));
    //     $filename = 'TechBench-'.$date;

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.view', ['Application', $filename]));
    //     $response->assertSuccessful();
    // }
}
