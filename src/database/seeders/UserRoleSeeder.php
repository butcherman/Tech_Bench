<?php

namespace Database\Seeders;

use App\Features\FileLinkFeature;
use App\Features\PublicTechTipFeature;
use App\Features\TechTipCommentFeature;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Create default user roles.
     */
    public function run(): void
    {
        $this->createUserRolePermissionCategories();
        $this->createUserRolePermissionTypes();
        $this->createDefaultUserRoles();
        $this->addUserRolePermissions();
    }

    /**
     * User Role Permission Categories
     */
    private function createUserRolePermissionCategories(): void
    {
        $defaultCategories = [
            [
                'role_cat_id' => 1,
                'category' => 'Administration',
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'role_cat_id' => 2,
                'category' => 'Customers',
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'role_cat_id' => 3,
                'category' => 'Tech Tips',
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'role_cat_id' => 4,
                'category' => 'File Links',
                'feature_name' => FileLinkFeature::class,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];

        DB::table('user_role_permission_categories')
            ->insertOrIgnore($defaultCategories);
    }

    /**
     * User Role Permission Types
     */
    private function createUserRolePermissionTypes(): void
    {
        $defaultData = [
            //  Administrative Permissions
            [
                'perm_type_id' => 1,
                'role_cat_id' => 1,
                'description' => 'App Settings',
                'is_admin_link' => 1,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 2,
                'role_cat_id' => 1,
                'description' => 'Manage Users',
                'is_admin_link' => 1,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 3,
                'role_cat_id' => 1,
                'description' => 'Manage Permissions',
                'is_admin_link' => 1,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 4,
                'role_cat_id' => 1,
                'description' => 'Run Reports',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 5,
                'role_cat_id' => 1,
                'description' => 'Manage Equipment',
                'is_admin_link' => 1,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            //  Customer Permissions
            [
                'perm_type_id' => 6,
                'role_cat_id' => 2,
                'description' => 'Manage Customers',
                'is_admin_link' => 1,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 7,
                'role_cat_id' => 2,
                'description' => 'Add Customer',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 8,
                'role_cat_id' => 2,
                'description' => 'Update Customer',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 9,
                'role_cat_id' => 2,
                'description' => 'Deactivate Customer',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 10,
                'role_cat_id' => 2,
                'description' => 'Delete Customer',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            //  Customer Equipment Permissions
            [
                'perm_type_id' => 11,
                'role_cat_id' => 2,
                'description' => 'Add Customer Equipment',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 12,
                'role_cat_id' => 2,
                'description' => 'Edit Customer Equipment',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 13,
                'role_cat_id' => 2,
                'description' => 'Delete Customer Equipment',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            //  Customer Contact Permissions
            [
                'perm_type_id' => 14,
                'role_cat_id' => 2,
                'description' => 'Add Customer Contact',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 15,
                'role_cat_id' => 2,
                'description' => 'Edit Customer Contact',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 16,
                'role_cat_id' => 2,
                'description' => 'Delete Customer Contact',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            //  Customer Notes Permissions
            [
                'perm_type_id' => 17,
                'role_cat_id' => 2,
                'description' => 'Add Customer Note',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 18,
                'role_cat_id' => 2,
                'description' => 'Edit Customer Note',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 19,
                'role_cat_id' => 2,
                'description' => 'Delete Customer Note',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            //  Customer Notes Permissions
            [
                'perm_type_id' => 20,
                'role_cat_id' => 2,
                'description' => 'Add Customer File',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 21,
                'role_cat_id' => 2,
                'description' => 'Edit Customer File',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 22,
                'role_cat_id' => 2,
                'description' => 'Delete Customer File',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            //  Tech Tips Permissions
            [
                'perm_type_id' => 23,
                'role_cat_id' => 3,
                'description' => 'Add Tech Tip',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 24,
                'role_cat_id' => 3,
                'description' => 'Edit Tech Tip',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 25,
                'role_cat_id' => 3,
                'description' => 'Delete Tech Tip',
                'is_admin_link' => 0,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 26,
                'role_cat_id' => 3,
                'description' => 'Manage Tech Tips',
                'is_admin_link' => 1,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 27,
                'role_cat_id' => 3,
                'description' => 'Comment on Tech Tip',
                'is_admin_link' => 0,
                'feature_name' => TechTipCommentFeature::class,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 28,
                'role_cat_id' => 3,
                'description' => 'Add Public Tech Tip',
                'feature_name' => PublicTechTipFeature::class,
                'is_admin_link' => 0,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            // File Links
            [
                'perm_type_id' => 29,
                'role_cat_id' => 4,
                'description' => 'Use File Links',
                'is_admin_link' => 0,
                'feature_name' => FileLinkFeature::class,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'perm_type_id' => 30,
                'role_cat_id' => 4,
                'description' => 'Manage File Links',
                'is_admin_link' => 1,
                'feature_name' => FileLinkFeature::class,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            // Customer Equipment Workbooks
            [
                'perm_type_id' => 31,
                'role_cat_id' => 1,
                'description' => 'Manage Equipment Workbooks',
                'is_admin_link' => 1,
                'feature_name' => null,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];

        // Insert the data if it does not already exist
        DB::table('user_role_permission_types')->insertOrIgnore($defaultData);
    }

    /**
     * Default User Roles.
     */
    private function createDefaultUserRoles(): void
    {
        $defaultData = [
            [
                'role_id' => 1,
                'name' => 'Installer',
                'description' => 'All Access Administrator',
                'allow_edit' => 0,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'role_id' => 2,
                'name' => 'Administrator',
                'description' => 'System Administrator',
                'allow_edit' => 0,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'role_id' => 3,
                'name' => 'Reports',
                'description' => 'User who can run reports',
                'allow_edit' => 0,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'role_id' => 4,
                'name' => 'Tech',
                'description' => 'Standard User',
                'allow_edit' => 0,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];

        DB::table('user_roles')->insertOrIgnore($defaultData);
    }

    /**
     * Add default permissions to the Default Roles.
     */
    private function addUserRolePermissions(): void
    {

        $roleList = UserRole::all();
        $permissionList = UserRolePermissionType::all();

        // Cycle through each role and add the default permissions
        foreach ($roleList as $role) {
            // Cycle through the default permissions
            foreach ($permissionList as $perm) {
                // Verify the permission does not already exist
                $exists = UserRolePermission::where('role_id', $role->role_id)
                    ->where('perm_type_id', $perm->perm_type_id)
                    ->first();

                if (! $exists) {
                    $allowed = $this->getDefaultValue($role, $perm);
                    UserRolePermission::create([
                        'role_id' => $role->role_id,
                        'perm_type_id' => $perm->perm_type_id,
                        'allow' => $allowed,
                    ]);
                }
            }
        }

    }

    /**
     * Get the default value for the selected permission type
     */
    private function getDefaultValue(UserRole $role, UserRolePermissionType $perm): bool
    {
        $item = $this->defaultListItem($perm->perm_type_id);

        return match ($role->role_id) {
            1 => $item['installer'],
            2 => $item['administrator'],
            3 => $item['reports'],
            default => $item['other'],
        };
    }

    private function defaultListItem(int $permId): array
    {
        $defaultList = [
            1 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            2 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            3 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            4 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => false,
            ],
            5 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            6 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            7 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            8 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            9 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            10 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            11 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => true,
            ],
            12 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => true,
            ],
            13 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            14 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            15 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            16 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            17 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            18 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            19 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            20 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            21 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            22 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            23 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            24 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            25 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            26 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            27 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            28 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            29 => [
                'installer' => true,
                'administrator' => true,
                'reports' => true,
                'other' => true,
            ],
            30 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
            31 => [
                'installer' => true,
                'administrator' => true,
                'reports' => false,
                'other' => false,
            ],
        ];

        return $defaultList[$permId];
    }
}
