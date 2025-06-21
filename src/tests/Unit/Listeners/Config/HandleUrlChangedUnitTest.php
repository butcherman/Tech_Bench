<?php

namespace Tests\Unit\Listeners\Config;

use App\Actions\Admin\UpdateApplicationUrl;
use App\Events\Config\UrlChangedEvent;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;
use Tests\TestCase;

class HandleUrlChangedUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Log::shouldReceive('alert')->once();

        $this->mock(UpdateApplicationUrl::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->once()->with('https://new.url');
        });

        event(new UrlChangedEvent('https://new.url', 'https://old.url'));
    }
}
