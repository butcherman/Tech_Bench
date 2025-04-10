<?php

namespace Tests\Unit\Jobs\User;

use App\Actions\User\BuildUserSettings;
use App\Jobs\User\CreateUserSettingsJob;
use App\Models\User;
use App\Models\UserSettingType;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateUserSettingsJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        DB::table('users')->insert([
            'user_id' => 2,
            'role_id' => 2,
            'username' => 'testUser',
            'first_name' => 'test',
            'last_name' => 'user',
            'email' => 'test@noem.com',
        ]);

        $testUser = User::find(2);

        $this->mock(
            BuildUserSettings::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('__invoke')->once()->with(User::class);
            }
        );

        CreateUserSettingsJob::dispatch($testUser);
    }
}
