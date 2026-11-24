<?php

namespace Tests\Unit\Models;

use App\Mail\Auth\VerificationCodeMail;
use App\Models\Customer;
use App\Models\DeviceToken;
use App\Models\FileLink;
use App\Models\TechTip;
use App\Models\User;
use App\Models\UserCustomerRecent;
use App\Models\UserLogin;
use App\Models\UserRole;
use App\Models\UserSetting;
use App\Models\UserTechTipRecent;
use App\Models\UserVerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserUnitTest extends TestCase
{
    /** @var User */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = User::find(1);
    }

    /*
    |---------------------------------------------------------------------------
    | Route Model Binding Key
    |---------------------------------------------------------------------------
    */
    public function test_route_binding_key(): void
    {
        $this->assertEquals($this->model->getRouteKeyName(), 'username');
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('full_name', $this->model->toArray());
        $this->assertArrayHasKey('initials', $this->model->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
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
        Event::fake();

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

    public function test_user_settings_relationship(): void
    {
        $settings = UserSetting::where('user_id', $this->model->user_id)->get();

        $this->assertEquals(
            $this->model->UserSettings->toArray(),
            $settings->toArray()
        );
    }

    public function test_user_role_relationship(): void
    {
        $role = UserRole::where('role_id', $this->model->role_id)->first();

        $this->assertEquals($this->model->UserRole, $role);
    }

    public function test_recent_tech_tips_relationship(): void
    {
        $tips = TechTip::factory()->count(2)->create();

        UserTechTipRecent::create([
            'user_id' => $this->model->user_id,
            'tip_id' => $tips[0]->tip_id,
        ]);
        UserTechTipRecent::create([
            'user_id' => $this->model->user_id,
            'tip_id' => $tips[1]->tip_id,
        ]);

        $recents = UserTechTipRecent::where('user_id', $this->model->user_id)
            ->get();

        $this->assertEquals(
            $recents->only('tip_id'),
            $this->model->RecentTechTips->only('tip_id')
        );
    }

    public function test_recent_customers_relationship(): void
    {
        $customers = Customer::factory()->count(2)->create();

        UserCustomerRecent::create([
            'user_id' => $this->model->user_id,
            'cust_id' => $customers[0]->cust_id,
        ]);
        UserCustomerRecent::create([
            'user_id' => $this->model->user_id,
            'cust_id' => $customers[1]->cust_id,
        ]);

        $recents = UserCustomerRecent::where('user_id', $this->model->user_id)
            ->get();

        $this->assertEquals(
            $recents->only('cust_id'),
            $this->model->RecentCustomers->only('cust_id')
        );
    }

    public function test_tech_tip_bookmarks_relationship(): void
    {
        $tipList = TechTip::factory()->count(5)->create();
        $tipList->each(function ($tip) {
            DB::table('user_tech_tip_bookmarks')->insert([
                'tip_id' => $tip->tip_id,
                'user_id' => $this->model->user_id,
            ]);
        });

        $this->assertCount(5, $this->model->TechTipBookmarks);
    }

    public function test_customer_bookmarks_relationship(): void
    {
        $custList = Customer::factory()->count(5)->create();
        $custList->each(function ($cust) {
            DB::table('user_customer_bookmarks')->insert([
                'cust_id' => $cust->cust_id,
                'user_id' => $this->model->user_id,
            ]);
        });

        $this->assertCount(5, $this->model->CustomerBookmarks);
    }

    public function test_file_links_relationship(): void
    {
        $linkList = FileLink::factory()
            ->count(5)
            ->create(['user_id' => $this->model->user_id]);

        $this->assertEquals(
            $linkList->makeHidden([
                'email_on_visit',
                'link_hash',
                'cust_id',
            ])->toArray(),
            $this->model->FileLinks->makeHidden([
                'email_on_visit',
                'link_hash',
                'cust_id',
            ])->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Model Methods
    |---------------------------------------------------------------------------
    */
    public function test_check_user_setting(): void
    {
        $settingName = 'Receive Email Notifications';
        $this->assertTrue($this->model->checkUserSetting($settingName));
    }

    public function test_check_user_setting_with_setting_missing(): void
    {
        $settingName = 'Receive Email Notifications';
        DB::table('user_settings')
            ->where('user_id', $this->model->user_id)->delete();

        $this->assertFalse($this->model->checkUserSetting($settingName));
    }

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

    public function test_generate_verification_code_again(): void
    {
        Mail::fake();

        $this->model->generateVerificationCode();
        $currentCode = UserVerificationCode::where(
            'user_id',
            $this->model->user_id
        )->first();

        // Re-submit the generate command
        $this->model->generateVerificationCode();

        $this->assertNotEquals(
            $currentCode->toArray(),
            $this->model->UserVerificationCode->toArray()
        );

        Mail::assertQueued(VerificationCodeMail::class);
    }

    public function test_validate_verification_code_pass(): void
    {
        UserVerificationCode::createQuietly([
            'user_id' => $this->model->user_id,
            'code' => '123456',
        ]);

        $this->assertTrue($this->model->validateVerificationCode(123456));
    }

    public function test_validate_verification_code_fail(): void
    {
        UserVerificationCode::createQuietly([
            'user_id' => $this->model->user_id,
            'code' => '123456',
        ]);

        $this->assertFalse($this->model->validateVerificationCode(654321));
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
