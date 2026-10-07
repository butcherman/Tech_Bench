<?php

namespace Tests\Unit\Services\Maintenance;

use App\Enums\ContainerList;
use App\Services\Maintenance\DockerControlService;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DockerControlServiceUnitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.docker_manager.url', 'http://docker-manager.test');
        Config::set('services.docker_manager.api_key', 'test-api-key');
    }

    /*
    |---------------------------------------------------------------------------
    | getDockerStatus()
    |---------------------------------------------------------------------------
    */
    public function test_get_docker_status(): void
    {
        Http::fake([
            'http://docker-manager.test/containers' => Http::response([
                'containers' => [
                    [
                        'id' => 'abc123',
                        'name' => 'redis',
                        'status' => 'running',
                    ],
                    [
                        'id' => 'def456',
                        'name' => 'mysql',
                        'status' => 'running',
                    ],
                ],
            ], 200),
        ]);

        $service = new DockerControlService;

        $result = $service->getDockerStatus();

        $this->assertEquals($result, [
            [
                'id' => 'abc123',
                'name' => 'redis',
                'status' => 'running',
            ],
            [
                'id' => 'def456',
                'name' => 'mysql',
                'status' => 'running',
            ],
        ]);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && $request->url() === 'http://docker-manager.test/containers'
                && $request->hasHeader(
                    'Authorization',
                    'Bearer test-api-key'
                );
        });
    }

    public function test_get_docker_status_throws_for_failed_response(): void
    {
        Http::fake([
            'http://docker-manager.test/containers' => Http::response(
                ['message' => 'Docker Manager unavailable'],
                500
            ),
        ]);

        $service = new DockerControlService;

        $this->expectException(RequestException::class);

        $service->getDockerStatus();
    }

    /*
    |---------------------------------------------------------------------------
    | rebootContainer()
    |---------------------------------------------------------------------------
    */
    public function test_reboot_container_sends_restart_request(): void
    {
        Http::fake([
            'http://docker-manager.test/containers/*/restart' => Http::response([], 200),
        ]);

        $service = new DockerControlService;

        $service->rebootContainer(ContainerList::Redis);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'POST'
                && $request->url() === 'http://docker-manager.test/containers/redis/restart'
                && $request->hasHeader(
                    'Authorization',
                    'Bearer test-api-key'
                );
        });
    }

    public function test_reboot_container_throws_for_failed_response(): void
    {
        Http::fake([
            'http://docker-manager.test/containers/*/restart' => Http::response(
                ['message' => 'Unable to restart container'],
                500
            ),
        ]);

        $this->expectException(RequestException::class);

        $service = new DockerControlService;
        $service->rebootContainer(ContainerList::Redis);
    }
}
