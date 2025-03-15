<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\User;
use App\Models\UserCustomerRecent;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserCustomerRecentUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Test Model Pruning
    |---------------------------------------------------------------------------
    */
    public function test_prunable(): void
    {
        $user = User::factory()->create();
        $custList = Customer::factory()->count(10)->create();

        UserCustomerRecent::create([
            'cust_id' => $custList[0]->cust_id,
            'user_id' => $user->user_id,
        ]);
        UserCustomerRecent::create([
            'cust_id' => $custList[1]->cust_id,
            'user_id' => $user->user_id,
        ]);
        UserCustomerRecent::create([
            'cust_id' => $custList[2]->cust_id,
            'user_id' => $user->user_id,
        ]);
        UserCustomerRecent::create([
            'cust_id' => $custList[3]->cust_id,
            'user_id' => $user->user_id,
        ]);
        UserCustomerRecent::create([
            'cust_id' => $custList[4]->cust_id,
            'user_id' => $user->user_id,
        ]);
        UserCustomerRecent::create([
            'cust_id' => $custList[5]->cust_id,
            'user_id' => $user->user_id,
        ]);

        $this->travel(100)->days();

        UserCustomerRecent::create([
            'cust_id' => $custList[6]->cust_id,
            'user_id' => $user->user_id,
        ]);
        UserCustomerRecent::create([
            'cust_id' => $custList[7]->cust_id,
            'user_id' => $user->user_id,
        ]);
        UserCustomerRecent::create([
            'cust_id' => $custList[8]->cust_id,
            'user_id' => $user->user_id,
        ]);
        UserCustomerRecent::create([
            'cust_id' => $custList[9]->cust_id,
            'user_id' => $user->user_id,
        ]);

        Artisan::call('model:prune', ['--model' => UserCustomerRecent::class]);

        $recentLeft = UserCustomerRecent::where('user_id', $user->user_id)->get();

        $this->assertCount(4, $recentLeft);
    }
}
