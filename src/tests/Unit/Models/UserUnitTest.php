<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserRole;
use App\Notifications\User\SendAuthCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = User::find(1);
    }

    /**
     * Route Model Binding Key
     */
    public function test_route_binding_key()
    {
        $this->assertEquals($this->model->getRouteKeyName(), 'username');
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('full_name', $this->model->toArray());
        $this->assertArrayHasKey('initials', $this->model->toArray());
        $this->assertArrayHasKey('role_name', $this->model->toArray());
    }

    /**
     * Model Relationships
     */
    public function test_user_role_relationship()
    {
        $role = UserRole::where('role_id', $this->model->role_id)->first();
        $this->assertEquals($this->model->UserRole, $role);
    }

    /**
     * Additional Model Methods
     */
    public function test_get_new_expire_time()
    {
        // Test Immediate Expire
        $yesterday = Carbon::yesterday()->format('M d Y');
        $this->assertEquals($this->model->getNewExpireTime(true)->format('M d Y'), $yesterday);

        // Test Future Expire
        $future = Carbon::now()->addDays(config('auth.passwords.settings.expire'))->format('M d Y');
        $this->assertEquals($this->model->getNewExpireTime()->format('M d Y'), $future);
    }

    public function test_generate_verification_code()
    {
        Notification::fake();

        $this->model->generateVerificationCode();

        Notification::assertSentTo($this->model, SendAuthCode::class);
    }
}
