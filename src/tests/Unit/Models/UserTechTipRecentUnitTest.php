<?php

namespace Tests\Unit\Models;

use App\Models\TechTip;
use App\Models\User;
use App\Models\UserTechTipRecent;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserTechTipRecentUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Test Model Pruning
    |---------------------------------------------------------------------------
    */
    public function test_prunable(): void
    {
        $user = User::factory()->create();
        $tipList = TechTip::factory()->count(10)->create();

        // $this->travel(-180)->days();

        UserTechTipRecent::create([
            'tip_id' => $tipList[0]->tip_id,
            'user_id' => $user->user_id,
        ]);
        UserTechTipRecent::create([
            'tip_id' => $tipList[1]->tip_id,
            'user_id' => $user->user_id,
        ]);
        UserTechTipRecent::create([
            'tip_id' => $tipList[2]->tip_id,
            'user_id' => $user->user_id,
        ]);
        UserTechTipRecent::create([
            'tip_id' => $tipList[3]->tip_id,
            'user_id' => $user->user_id,
        ]);
        UserTechTipRecent::create([
            'tip_id' => $tipList[4]->tip_id,
            'user_id' => $user->user_id,
        ]);
        UserTechTipRecent::create([
            'tip_id' => $tipList[5]->tip_id,
            'user_id' => $user->user_id,
        ]);

        $this->travel(100)->days();

        UserTechTipRecent::create([
            'tip_id' => $tipList[6]->tip_id,
            'user_id' => $user->user_id,
        ]);
        UserTechTipRecent::create([
            'tip_id' => $tipList[7]->tip_id,
            'user_id' => $user->user_id,
        ]);
        UserTechTipRecent::create([
            'tip_id' => $tipList[8]->tip_id,
            'user_id' => $user->user_id,
        ]);
        UserTechTipRecent::create([
            'tip_id' => $tipList[9]->tip_id,
            'user_id' => $user->user_id,
        ]);

        Artisan::call('model:prune', ['--model' => UserTechTipRecent::class]);

        $recentLeft = UserTechTipRecent::where('user_id', $user->user_id)->get();

        $this->assertCount(4, $recentLeft);
    }
}
