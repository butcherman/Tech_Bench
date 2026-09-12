<?php

namespace Tests\Feature\Maintenance\Logs;

use App\DTO\Maintenance\LogSnapshot;
use App\Exceptions\Maintenance\LogFileMissingException;
use App\Models\User;
use App\Services\Maintenance\LogUtilitiesService;
use Illuminate\Support\Facades\Exceptions;
use Tests\TestCase;

class LogLoadMoreTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('maint.logs.load', 'test-log'));
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('maint.logs.load', 'test-log'));
        $response->assertForbidden();
    }

    public function test_invoke_missing_log_file(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $this->expectException(LogFileMissingException::class);

        $response = $this->withoutExceptionHandling()
            ->actingAs($user)
            ->get(route('maint.logs.load', 'test-log'));

        $response->assertNotFound();

        Exceptions::assertReported(LogFileMissingException::class);
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $snapshot = new LogSnapshot(12345);

        $mock = $this->mock(LogUtilitiesService::class);
        $mock->expects('validateLogFile')->with('test-log')->andReturn(true);
        $mock->expects('snapshot')->once()->andReturns($snapshot);
        $mock->shouldReceive('query')->once();

        $response = $this->actingAs($user)
            ->get(route('maint.logs.load', 'test-log').'?page=1');

        $response->assertSuccessful();
    }

    public function test_invoke_with_snapshot_already_built(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $mock = $this->mock(LogUtilitiesService::class);
        $mock->expects('validateLogFile')->with('test-log')->andReturn(true);
        $mock->expects('query')->once();

        $response = $this->actingAs($user)
            ->get(route('maint.logs.load', 'test-log').'?page=1&snapshot=12345');

        $response->assertSuccessful();
    }
}
