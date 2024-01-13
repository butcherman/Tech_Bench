<?php

use App\Models\UserRolePermissionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserRolePermissionCategoriesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('user_role_permission_categories', function (Blueprint $table) {
            $table->id('role_cat_id');
            $table->text('category');
            $table->timestamps();
        });

        //  Add the category column to user role permision types
        Schema::table('user_role_permission_types', function (Blueprint $table) {
            $table->unsignedBigInteger('role_cat_id')->after('perm_type_id')->nullable();
            $table->foreign('role_cat_id')->references('role_cat_id')->on('user_role_permission_categories')->onUpdate('cascade');
        });

        /**
         * Default Data
         */
        $defaultCategories = [
            [
                'role_cat_id' => 1,
                'category' => 'Administration',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'role_cat_id' => 2,
                'category' => 'Customers',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'role_cat_id' => 3,
                'category' => 'Tech Tips',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];
        DB::table('user_role_permission_categories')->insert($defaultCategories);

        //  Add categories to default User Role Permission Types
        $defaultPermissions = [
            //  Administrative Permissions
            ['perm_type_id' => 1,  'role_cat_id' => 1],
            ['perm_type_id' => 2,  'role_cat_id' => 1],
            ['perm_type_id' => 3,  'role_cat_id' => 1],
            ['perm_type_id' => 4,  'role_cat_id' => 1],
            ['perm_type_id' => 5,  'role_cat_id' => 1],
            //  Customer Permissions
            ['perm_type_id' => 6,  'role_cat_id' => 2],
            ['perm_type_id' => 7,  'role_cat_id' => 2],
            ['perm_type_id' => 8,  'role_cat_id' => 2],
            ['perm_type_id' => 9,  'role_cat_id' => 2],
            ['perm_type_id' => 10, 'role_cat_id' => 2],
            ['perm_type_id' => 11, 'role_cat_id' => 2],
            ['perm_type_id' => 12, 'role_cat_id' => 2],
            ['perm_type_id' => 13, 'role_cat_id' => 2],
            ['perm_type_id' => 14, 'role_cat_id' => 2],
            ['perm_type_id' => 15, 'role_cat_id' => 2],
            ['perm_type_id' => 16, 'role_cat_id' => 2],
            ['perm_type_id' => 17, 'role_cat_id' => 2],
            ['perm_type_id' => 18, 'role_cat_id' => 2],
            ['perm_type_id' => 19, 'role_cat_id' => 2],
            ['perm_type_id' => 20, 'role_cat_id' => 2],
            ['perm_type_id' => 21, 'role_cat_id' => 2],
            ['perm_type_id' => 22, 'role_cat_id' => 2],
            ['perm_type_id' => 23, 'role_cat_id' => 3],
            ['perm_type_id' => 24, 'role_cat_id' => 3],
            ['perm_type_id' => 25, 'role_cat_id' => 3],
            ['perm_type_id' => 26, 'role_cat_id' => 3],
            ['perm_type_id' => 27, 'role_cat_id' => 3],
        ];
        foreach ($defaultPermissions as $perm) {
            UserRolePermissionType::where('perm_type_id', $perm['perm_type_id'])
                ->update(['role_cat_id' => $perm['role_cat_id']]);
        }
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::table('user_role_permission_types', function (Blueprint $table) {
            $table->dropForeign(['role_cat_id']);
            $table->dropColumn('role_cat_id');
        });

        Schema::dropIfExists('user_role_permission_categories');
    }
}
