<?php

namespace Tests\Unit\Admin;

use App\Domains\Admin\PasswordPolicy as AdminPasswordPolicy;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;

class PasswordPolicy extends TestCase
{
    protected $testUsers;

    //  Seed the database with some default data and setup for testing
    public function setUp():void
    {
        Parent::setup();

        $this->testUsers = factory(User::class, 10)->create();
    }

    public function test_set_policy()
    {
        $data = new PasswordPolicyRequest([
            'expire' => 30,
        ]);

        $res  = (new AdminPasswordPolicy)->setPolicy($data);

        $this->assertTrue($res);
        foreach($this->testUsers as $user)
        {
            $this->assertDatabaseHas('users', [
                'user_id' => $user->user_id,
                // 'password_expires' => Carbon::now()->addDays(30)->toDateString(),
            ]);
        }
    }

    public function test_set_policy_to_null()
    {
        $data = new PasswordPolicyRequest([
            'expire' => 0,
        ]);

        $res  = (new AdminPasswordPolicy)->setPolicy($data);

        $this->assertTrue($res);
        foreach($this->testUsers as $user)
        {
            $this->assertDatabaseHas('users', [
                'user_id' => $user->user_id,
                'password_expires' => null,
            ]);
        }
    }
}
