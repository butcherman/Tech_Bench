<?php

use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionCategory;
use App\Models\UserRolePermissionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipment_workbooks', function (Blueprint $table) {
            $table->unsignedBigInteger('equip_id')->primary();
            $table->json('workbook_data')->nullable();
            $table->text('version_hash');
            $table->timestamps();
            $table->foreign('equip_id')
                ->references('equip_id')
                ->on('equipment_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        // Add Workbook Permissions to User Roles
        $category = UserRolePermissionCategory::where('category', 'Administration')
            ->first();
        $newPerm = UserRolePermissionType::create([
            'role_cat_id' => $category->role_cat_id,
            'description' => 'Manage Equipment Workbooks',
            'is_admin_link' => true,
            'feature_name' => null,
        ]);

        // Any Role that can currently edit equipment should be able to manage workbooks.
        $roleList = UserRole::all();
        $permType = UserRolePermissionType::where('description', 'Manage Equipment')
            ->first();

        foreach ($roleList as $role) {
            $equipPerm = UserRolePermission::where('role_id', $role->role_id)
                ->where('perm_type_id', $permType->perm_type_id)
                ->first();

            UserRolePermission::create([
                'role_id' => $role->role_id,
                'perm_type_id' => $newPerm->perm_type_id,
                'allow' => $equipPerm->allow,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        UserRolePermissionType::where('description', 'Manage Equipment Workbooks')
            ->delete();

        Schema::table('equipment_workbooks', function (Blueprint $table) {
            $table->dropForeign(['equip_id']);
        });

        Schema::dropIfExists('equipment_workbooks');
    }
};
