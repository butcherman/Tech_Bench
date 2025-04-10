<?php

namespace Tests\Feature\Maintenance\Logs;

use App\Exceptions\Maintenance\LogFileMissingException;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ViewLogTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $filename = 'TechBench-' . $date;

        $response = $this->get(route('maint.logs.show', [
            'Application',
            $filename,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $filename = 'TechBench-' . $date;

        $response = $this->actingAs($user)
            ->get(route('maint.logs.show', ['Application', $filename]));

        $response->assertStatus(403);
    }

    public function test_invoke_bad_channel(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $filename = 'TechBench-' . $date;

        $response = $this->actingAs($user)
            ->get(route('maint.logs.show', ['YourMom', $filename]));

        $response->assertStatus(404);

        Exceptions::assertReported(LogFileMissingException::class);
    }

    public function test_invoke_bad_file_name(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.logs.show', ['Application', 'yourMom.com']));

        $response->assertStatus(404);

        Exceptions::assertReported(LogFileMissingException::class);
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $filename = 'TechBench-' . $date;

        $response = $this->actingAs($user)
            ->get(route('maint.logs.show', ['Application', $filename]));

        $response->assertSuccessful()
            ->assertInertia(fn(Assert $page) => $page
                ->component('Maint/AppLogView')
                ->has('channel')
                ->has('log-file'));
    }
}
