<?php

namespace Tests\Feature\Maintenance\Logs;

use App\Actions\Maintenance\CalculateLogStatistics;
use App\Exceptions\Maintenance\LogFileMissingException;
use App\Models\User;
use App\Services\Maintenance\LogUtilitiesService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;

class LogsIndexTest extends TestCase
{
    /** @var LogUtilitiesService&Mock */
    protected LogUtilitiesService $svc;

    /** @var CalculateLogStatistics&Mock */
    protected CalculateLogStatistics $stats;

    protected function setUp(): void
    {
        parent::setUp();

        $this->svc = Mockery::mock(LogUtilitiesService::class);
        $this->stats = Mockery::mock(CalculateLogStatistics::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('maint.logs.index'));
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('maint.logs.index'));
        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        Carbon::setTestNow('2026-09-12 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $logFile = 'TechBench-2026-09-12';

        $svc = Mockery::mock(LogUtilitiesService::class);

        $svc->shouldReceive('validateLogFile')
            ->once()
            ->with($logFile)
            ->andReturnTrue();

        $svc->shouldReceive('getListOfLogFiles')
            ->once()
            ->andReturn([
                'TechBench-2026-09-12',
                'TechBench-2026-09-11',
            ]);

        $this->app->instance(LogUtilitiesService::class, $svc);

        $response = $this->actingAs($user)->get(route('maint.logs.index'));

        $response
            ->assertSuccessful()
            ->assertInertia(fn ($page) => $page
                ->component('Maint/Logs/Index')
                ->where('logFile', $logFile)
                ->where('logList', [
                    'TechBench-2026-09-12',
                    'TechBench-2026-09-11',
                ])
                ->where(
                    'loggingLevel',
                    config('logging.channels.app.level')
                )
            );

        Carbon::setTestNow();
    }

    public function test_invoke_with_log_file_defined(): void
    {
        Carbon::setTestNow('2026-09-12 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $logFile = 'TechBench-2026-09-11';

        $svc = Mockery::mock(LogUtilitiesService::class);

        $svc->shouldReceive('validateLogFile')
            ->once()
            ->with($logFile)
            ->andReturnTrue();

        $svc->shouldReceive('getListOfLogFiles')
            ->once()
            ->andReturn([
                'TechBench-2026-09-12',
                'TechBench-2026-09-11',
            ]);

        $this->app->instance(LogUtilitiesService::class, $svc);

        $response = $this->actingAs($user)
            ->get(route('maint.logs.show', [$logFile]));

        $response
            ->assertSuccessful()
            ->assertInertia(fn ($page) => $page
                ->component('Maint/Logs/Index')
                ->where('logFile', $logFile)
                ->where('logList', [
                    'TechBench-2026-09-12',
                    'TechBench-2026-09-11',
                ])
                ->where(
                    'loggingLevel',
                    config('logging.channels.app.level')
                )
            );

        Carbon::setTestNow();
    }

    public function test_invoke_with_invalid_log_file_defined(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $logFile = 'Invalid-2026-09-11';

        $svc = Mockery::mock(LogUtilitiesService::class);

        $svc->shouldReceive('validateLogFile')
            ->once()
            ->with($logFile)
            ->andReturnFalse();

        $this->app->instance(LogUtilitiesService::class, $svc);

        $this->expectException(LogFileMissingException::class);

        $response = $this->withoutExceptionHandling()->actingAs($user)
            ->get(route('maint.logs.show', [$logFile]));

        $response->assertNotFound();

        Exceptions::assertReported(LogFileMissingException::class);
    }
}
