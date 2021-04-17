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
        Schema::create('user_role_permission_types', function(Blueprint $table) {
            $table->id('perm_type_id');
            $table->text('description');
            $table->boolean('is_admin_link')->default(0);
            $table->timestamps();
        });

        //  Insert default roles permission types
        $defaultData = [
            ['perm_type_id' => 1,  'description' => 'Manage Users',        'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 2,  'description' => 'Manage Permissions',  'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 3,  'description' => 'Run Reports',         'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 4,  'description' => 'Add Customer',        'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 5,  'description' => 'Manage Customers',    'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 6,  'description' => 'Deactivate Customer', 'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 7,  'description' => 'Use File Links',      'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 8,  'description' => 'Create Tech Tip',     'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 9,  'description' => 'Edit Tech Tip',       'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 10, 'description' => 'Delete Tech Tip',     'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 11, 'description' => 'Manage Equipment',    'is_admin_link' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 12, 'description' => 'Update Customer',     'is_admin_link' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
        ];
        // UserRolePermissionTypes::insert($defaultData);
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
