<?php

namespace Tests\Unit\Models;

use App\Mail\Auth\VerificationCodeMail;
use App\Models\DeviceToken;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\UserRole;
use App\Models\UserVerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserUnitTest extends TestCase
{
    /** @var User */
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = User::find(1);
    }

    /**
     * Route Model Binding Key
     */
    public function test_route_binding_key(): void
    {
        $this->assertEquals($this->model->getRouteKeyName(), 'username');
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('full_name', $this->model->toArray());
        $this->assertArrayHasKey('initials', $this->model->toArray());
        // $this->assertArrayHasKey('role_name', $this->model->toArray());
    }

    /**
     * Model Relationships
     */
    public function test_device_token_relationship(): void
    {
        $token = DeviceToken::factory()
            ->create(['user_id' => $this->model->user_id])
            ->makeHidden(['user']);

        $this->assertEquals(
            $this->model->DeviceTokens[0]->toArray(),
            $token->toArray()
        );
    }

    public function test_user_verification_code_relationship(): void
    {
        $code = UserVerificationCode::create([
            'user_id' => $this->model->user_id,
            'code' => '1234',
        ]);

        $this->assertEquals(
            $this->model->UserVerificationCode->only(['user_id', 'code']),
            $code->makeHidden('User')->only(['user_id', 'code'])
        );
    }

    public function test_user_logins_relationship(): void
    {
        UserLogin::create([
            'user_id' => $this->model->user_id,
            'ip_address' => '127.0.0.1',
        ]);

        $this->assertCount(1, $this->model->UserLogins->toArray());
    }

    public function test_user_role_relationship(): void
    {
        $role = UserRole::where('role_id', $this->model->role_id)->first();

        $this->assertEquals($this->model->UserRole, $role);
    }

    /**
     * Additional Model Methods
     */
    public function test_get_new_expire_time(): void
    {
        // Test Immediate Expire
        $yesterday = Carbon::yesterday()->format('M d Y');
        $this->assertEquals(
            $this->model->getNewExpireTime(true)->format('M d Y'),
            $yesterday
        );

        // Test Future Expire
        $future = Carbon::now()
            ->addDays(config('auth.passwords.settings.expire'))
            ->format('M d Y');

        $this->assertEquals(
            $this->model->getNewExpireTime()->format('M d Y'),
            $future
        );
    }

    public function test_generate_verification_code(): void
    {
        Mail::fake();

        $this->model->generateVerificationCode();

        Mail::assertQueued(VerificationCodeMail::class);
    }

    public function test_validate_device_token(): void
    {
        $token = DeviceToken::factory()
            ->create(['user_id' => $this->model->user_id]);

        $this->assertTrue($this->model->validateDeviceToken($token->token));
    }

    public function test_validate_device_token_invalid(): void
    {
        DeviceToken::factory()
            ->create(['user_id' => $this->model->user_id]);

        $token = DeviceToken::factory()->make();

        $this->assertFalse($this->model->validateDeviceToken($token->token));
    }

    public function test_get_admin_load(): void
    {
        $adminLoad = $this->model->getAdminLoad()->toArray();

        $this->assertArrayHasKey('role_id', $adminLoad);
        $this->assertArrayHasKey('created_at', $adminLoad);
        $this->assertArrayHasKey('updated_at', $adminLoad);
        $this->assertArrayHasKey('deleted_at', $adminLoad);
    }

    public function test_get_login_history()
    {
        UserLogin::factory()
            ->count(10)
            ->create(['user_id' => $this->model->user_id]);

        $this->assertCount(10, $this->model->getLoginHistory());
    }

    public function test_get_last_login()
    {
        for ($i = 0; $i < 10; $i++) {
            UserLogin::factory()->create(['user_id' => $this->model->user_id]);
            $this->travel(2)->days();
        }

        $lastLogin = UserLogin::where('user_id', $this->model->user_id)
            ->get()
            ->last();

        $this->assertEquals(
            $lastLogin->toArray(),
            $this->model->getLastLogin()->toArray()
        );
    }
}
