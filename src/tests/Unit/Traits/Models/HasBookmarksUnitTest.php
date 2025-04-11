<?php

namespace Tests\Unit\Traits\Models;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HasBookmarksUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | isFav()
    |---------------------------------------------------------------------------
    */
    public function test_is_fav_true(): void
    {
        $testUser = User::factory()->create();
        $testTip = TechTip::factory()->create();

        DB::table('user_tech_tip_bookmarks')->insert([
            'tip_id' => $testTip->tip_id,
            'user_id' => $testUser->user_id,
        ]);

        $this->assertTrue($testTip->isFav($testUser));
    }

    public function test_is_fav_false(): void
    {
        $testUser = User::factory()->create();
        $testTip = TechTip::factory()->create();

        $this->assertFalse($testTip->isFav($testUser));
    }

    /*
    |---------------------------------------------------------------------------
    | toggleBookmark()
    |---------------------------------------------------------------------------
    */
    public function test_toggle_bookmark_set(): void
    {
        $testUser = User::factory()->create();
        $testTip = TechTip::factory()->create();

        $testTip->toggleBookmark($testUser, true);

        $this->assertDatabaseHas('user_tech_tip_bookmarks', [
            'user_id' => $testUser->user_id,
            'tip_id' => $testTip->tip_id,
        ]);
    }

    public function test_toggle_bookmark_clear(): void
    {
        $testUser = User::factory()->create();
        $testTip = TechTip::factory()->create();

        DB::table('user_tech_tip_bookmarks')->insert([
            'tip_id' => $testTip->tip_id,
            'user_id' => $testUser->user_id,
        ]);

        $testTip->toggleBookmark($testUser, false);

        $this->assertDatabaseMissing('user_tech_tip_bookmarks', [
            'user_id' => $testUser->user_id,
            'tip_id' => $testTip->tip_id,
        ]);
    }
}
