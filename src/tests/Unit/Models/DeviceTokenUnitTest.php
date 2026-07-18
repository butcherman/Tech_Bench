<?php

namespace Tests\Unit\Models;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DeviceTokenUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Prunable Models
    |---------------------------------------------------------------------------
    */
    public function test_prunable(): void
    {
        DeviceToken::factory()->count(40)->sequence(
            ['created_at' => now()->subDays(200)],
            ['created_at' => now()->subDays(181)],
            ['created_at' => now()->subDays(91)],
            ['created_at' => now()->subDays(30)],
        )->create();

        Artisan::call('model:prune', ['--model' => DeviceToken::class]);

        $modelList = DeviceToken::all();

        $this->assertCount(20, $modelList);
    }
}
