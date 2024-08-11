<?php

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
        ]);

        // Update Permission lists, or create if new install
        UserRolePermissionType::updateOrCreate(
            [
                'description' => 'Use File Links',
            ],
            [
                'role_cat_id' => $newCategory->role_cat_id,
                'is_admin_link' => false,
                'feature_name' => 'App\Features\FileLinkFeature',
            ]
        );

        UserRolePermissionType::updateOrCreate(
            [
                'description' => 'Manage File Links',
            ],
            [
                'role_cat_id' => $newCategory->role_cat_id,
                'is_admin_link' => true,
                'feature_name' => 'App\Features\FileLinkFeature',
            ]
        );
    }
};
