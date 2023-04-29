<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserRolePermissionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_permission_types', function (Blueprint $table) {
            $table->id('perm_type_id');
            $table->text('description');
            $table->boolean('is_admin_link')->default(0);
            $table->timestamps();
        });

        //  Default roles permission types
        $defaultData = [
            //  Administrative Permissions
            ['perm_type_id' => 1,  'description' => 'App Settings',              'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 2,  'description' => 'Manage Users',              'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 3,  'description' => 'Manage Permissions',        'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 4,  'description' => 'Run Reports',               'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 5,  'description' => 'Manage Equipment',          'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            //  Customer Permissions
            ['perm_type_id' => 6,  'description' => 'Manage Customers',          'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 7,  'description' => 'Add Customer',              'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 8,  'description' => 'Update Customer',           'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 9,  'description' => 'Deactivate Customer',       'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 10, 'description' => 'Delete Customer',           'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            //  Customer Equipment Permissions
            ['perm_type_id' => 11, 'description' => 'Add Customer Equipment',    'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 12, 'description' => 'Edit Customer Equipment',   'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 13, 'description' => 'Delete Customer Equipment', 'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            //  Customer Contact Permissions
            ['perm_type_id' => 14, 'description' => 'Add Customer Contact',      'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 15, 'description' => 'Edit Customer Contact',     'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 16, 'description' => 'Delete Customer Contact',   'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            //  Customer Notes Permissions
            ['perm_type_id' => 17, 'description' => 'Add Customer Note',         'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 18, 'description' => 'Edit Customer Note',        'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 19, 'description' => 'Delete Customer Note',      'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            //  Customer Notes Permissions
            ['perm_type_id' => 20, 'description' => 'Add Customer File',         'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 21, 'description' => 'Edit Customer File',        'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 22, 'description' => 'Delete Customer File',      'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            //  Tech Tips Permissions
            ['perm_type_id' => 23, 'description' => 'Add Tech Tip',              'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 24, 'description' => 'Edit Tech Tip',             'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 25, 'description' => 'Delete Tech Tip',           'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 26, 'description' => 'Manage Tech Tips',          'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 27, 'description' => 'Comment on Tech Tip',       'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
        ];

        DB::table('user_role_permission_types')->insert($defaultData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_role_permission_types');
    }
}
