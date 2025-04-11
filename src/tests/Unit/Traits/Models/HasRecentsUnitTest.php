<?php

namespace Tests\Unit\Traits\Models;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HasRecentsUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | touchRecent()
    |---------------------------------------------------------------------------
    */
    public function test_touch_recent_attach(): void
    {
        $testUser = User::factory()->create();
        $testTip = TechTip::factory()->create();

        $testTip->touchRecent($testUser);

        $this->assertDatabaseHas('user_tech_tip_recents', [
            'tip_id' => $testTip->tip_id,
            'user_id' => $testUser->user_id,
        ]);
    }

    public function test_touch_recent_update(): void
    {
        $testUser = User::factory()->create();
        $testTip = TechTip::factory()->create();
        $testDate = '2020-01-01 22:00:00';

        DB::table('user_tech_tip_recents')->insert([
            'tip_id' => $testTip->tip_id,
            'user_id' => $testUser->user_id,
            'created_at' => $testDate,
            'updated_at' => $testDate,
        ]);

        $testTip->touchRecent($testUser);

        $this->assertDatabaseHas('user_tech_tip_recents', [
            'tip_id' => $testTip->tip_id,
            'user_id' => $testUser->user_id,
        ]);

        $this->assertDatabaseMissing('user_tech_tip_recents', [
            'tip_id' => $testTip->tip_id,
            'user_id' => $testUser->user_id,
            'created_at' => $testDate,
            'updated_at' => $testDate,
        ]);
    }
}
