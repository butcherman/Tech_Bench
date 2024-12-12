<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserVerificationCode;
use Tests\TestCase;

class UserVerificationCodeUnitTest extends TestCase
{
    /** @var UserVerificationCode */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = UserVerificationCode::create([
            'user_id' => User::factory()->create()->user_id,
            'code' => '1234',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Test Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_user_relationship(): void
    {
        $user = User::find($this->model->user_id);

        $this->assertEquals($user->toArray(), $this->model->User->toArray());
    }
}
