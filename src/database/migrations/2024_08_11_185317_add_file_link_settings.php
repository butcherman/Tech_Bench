<?php

use App\Features\FileLinkFeature;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionCategory;
use App\Models\UserRolePermissionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create a new Permission Category
        $newCategory = UserRolePermissionCategory::create([
            'category' => 'File Links',
            'feature_name' => FileLinkFeature::class,
        ]);

        // Update Permission lists, or create if new install
        $usePerm = UserRolePermissionType::updateOrCreate(
            [
                'description' => 'Use File Links',
            ],
            [
                'role_cat_id' => $newCategory->role_cat_id,
                'is_admin_link' => false,
                'feature_name' => FileLinkFeature::class,
            ]
        );

        $managePerm = UserRolePermissionType::updateOrCreate(
            [
                'description' => 'Manage File Links',
            ],
            [
                'role_cat_id' => $newCategory->role_cat_id,
                'is_admin_link' => true,
                'feature_name' => FileLinkFeature::class,
            ]
        );

        // Add File Link permissions to all roles
        $roleList = UserRole::all();
        foreach ($roleList as $role) {
            UserRolePermission::updateOrCreate(
                [
                    'role_id' => $role->role_id,
                    'perm_type_id' => $usePerm->perm_type_id
                ],
                ['allow' => true],
            );
            UserRolePermission::updateOrCreate(
                [
                    'role_id' => $role->role_id,
                    'perm_type_id' => $managePerm->perm_type_id
                ],
                ['allow' => $role->role_id <= 2 ? true : false],
            );
        }
    }
};
