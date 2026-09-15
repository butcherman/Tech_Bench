<?php

namespace Tests\Unit\Services\Maintenance;

use App\Enums\DiskEnum;
use App\Services\File\FileSystemSpace;
use App\Services\File\StorageUsageService;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class StorageUsageServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getUsage()
    |---------------------------------------------------------------------------
    */
    public function test_get_usage(): void
    {
        Storage::fake();

        $path = Storage::disk('local')->path('/');

        $mock = $this->mock(FileSystemSpace::class);

        $mock->shouldReceive('getDiskTotalSpace')->with($path)->andReturn(200);
        $mock->shouldReceive('getDiskFreeSpace')->with($path)->andReturn(100);

        $testObj = new StorageUsageService($mock);
        $res = $testObj->getUsage(DiskEnum::local);

        $this->assertEquals([
            'total' => 200,
            'used' => 100,
            'free' => 100,
            'used_percent' => 50,
        ], $res);
    }

    public function test_get_usage_total_space_fails(): void
    {
        Storage::fake();
        Exceptions::fake();

        $path = Storage::disk('local')->path('/');

        $mock = $this->mock(FileSystemSpace::class);

        $mock->shouldReceive('getDiskTotalSpace')->with($path)->andReturn(false);
        $mock->shouldReceive('getDiskFreeSpace')->with($path)->andReturn(100);

        $this->expectException(RuntimeException::class);

        $testObj = new StorageUsageService($mock);
        $testObj->getUsage(DiskEnum::local);

        Exceptions::assertReported(RuntimeException::class);
    }

    public function test_get_usage_free_space_fails(): void
    {
        Storage::fake();
        Exceptions::fake();

        $path = Storage::disk('local')->path('/');

        $mock = $this->mock(FileSystemSpace::class);

        $mock->shouldReceive('getDiskTotalSpace')->with($path)->andReturn(200);
        $mock->shouldReceive('getDiskFreeSpace')->with($path)->andReturn(false);

        $this->expectException(RuntimeException::class);

        $testObj = new StorageUsageService($mock);
        $testObj->getUsage(DiskEnum::local);

        Exceptions::assertReported(RuntimeException::class);
    }
}
