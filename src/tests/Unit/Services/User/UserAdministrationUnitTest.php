<?php

namespace Tests\Unit\Services\User;

use App\Jobs\User\CreateUserSettingsJob;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class UserAdministrationUnitTest extends TestCase
{
    public function test_get_all_users(): void
    {
        $this->seed(UserSeeder::class);

        $testObj = new UserAdministrationService;
        $response = $testObj->getAllUsers();

        $this->assertEquals(22, $response->count());
    }

    public function test_get_trashed(): void
    {
        $this->seed(UserSeeder::class);

        $testObj = new UserAdministrationService;
        $response = $testObj->getAllUsers(true);

        $this->assertEquals(5, $response->count());
    }

    public function test_create_user(): void
    {
        Bus::fake();

        $data = User::factory()
            ->make()
            ->only([
                'username',
                'first_name',
                'last_name',
                'email',
                'role_id',
            ]);

        $testObj = new UserAdministrationService;
        $newUser = $testObj->createUser(collect($data));

        $this->assertDatabaseHas('users', $data);
        $this->assertEquals($newUser->only([
            'username',
            'first_name',
            'last_name',
            'email',
            'role_id',
        ]), $data);

        Bus::assertDispatched(SendWelcomeEmailJob::class);
        Bus::assertDispatched(CreateUserSettingsJob::class);
    }

    public function test_create_user_no_email(): void
    {
        Bus::fake();

        $data = User::factory()
            ->make()
            ->only([
                'username',
                'first_name',
                'last_name',
                'email',
                'role_id',
            ]);

        $testObj = new UserAdministrationService;
        $newUser = $testObj->createUser(collect($data), true);

        $this->assertDatabaseHas('users', $data);
        $this->assertEquals($newUser->only([
            'username',
            'first_name',
            'last_name',
            'email',
            'role_id',
        ]), $data);

        Bus::assertNotDispatched(SendWelcomeEmailJob::class);
        Bus::assertDispatched(CreateUserSettingsJob::class);
    }
}
