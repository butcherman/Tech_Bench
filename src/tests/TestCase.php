<?php

namespace Tests;

use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->withoutMiddleware([
            // \Spatie\CookieConsent\CookieConsentMiddleware::class,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Change a Permission Value for the selected Role based on the
    | permission field name.
    |---------------------------------------------------------------------------
    */
    protected function changeRolePermission(
        int $roleId,
        string $permName,
        bool $value = false
    ): void {
        $permId = UserRolePermissionType::where('description', $permName)
            ->first()
            ->perm_type_id;

        UserRolePermission::where('role_id', $roleId)
            ->where('perm_type_id', $permId)
            ->update([
                'allow' => $value,
            ]);
    }
}
