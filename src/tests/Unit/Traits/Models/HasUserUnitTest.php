<?php

namespace Tests\Unit\Traits\Models;

use App\Models\CustomerFile;
use App\Models\TechTip;
use App\Models\User;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class HasUserUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | User() *Note* - only testing one model to make sure relationship returned
    |---------------------------------------------------------------------------
    */
    public function test_user(): void
    {
        $user = User::factory()->create();
        $file = CustomerFile::factory()->create(['user_id' => $user->user_id]);

        $this->assertEquals($user->toArray(), $file->User->toArray());
    }

    public function test_user_trashed(): void
    {
        $user = User::factory()->create();
        $file = CustomerFile::factory()->create(['user_id' => $user->user_id]);
        $user->delete();

        $this->assertEquals($user->toArray(), $file->User->toArray());
    }
}
