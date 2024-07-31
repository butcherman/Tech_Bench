<?php

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
        $permCategory = UserRolePermissionCategory::where('category', 'Tech Tips')
            ->first();

        $newPerm = UserRolePermissionType::create([
            'role_cat_id' => $permCategory->role_cat_id,
            'description' => 'Add Public Tech Tip',
            'is_admin_link' => false,
        ]);

        /**
         * Add this new permission to all roles.  Installer and Admin Roles  
         * will be allow, others will be false
         */
        $allRoles = UserRole::get();
        foreach ($allRoles as $role) {
            UserRolePermission::create([
                'role_id' => $role->role_id,
                'perm_type_id' => $newPerm->perm_type_id,
                'allow' => $role->role_id <= 2 ? true : false,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permType = UserRolePermission::where('description', 'Add Public Tech Tip')
            ->first();

        $permType->delete();
    }
};
