<?php

use App\UserRolePermissionTypes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            $table->increments('perm_type_id');
            $table->text('description');
            $table->timestamps();
        });

        //  Insert default roles permission types
        $defaultData = [
            ['perm_type_id' => 1,  'description' => 'Manage Users',        'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 2,  'description' => 'Manage Permissions',  'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 3,  'description' => 'Run Reports',         'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 4,  'description' => 'Add Customer',        'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 5,  'description' => 'Manage Customers',    'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 6,  'description' => 'Deactivate Customer', 'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 7,  'description' => 'Use File Links',      'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 8,  'description' => 'Create Tech Tip',     'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 9,  'description' => 'Edit Tech Tip',       'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 10, 'description' => 'Delete Tech Tip',     'created_at' => NOW(), 'updated_at' => NOW()],
            ['perm_type_id' => 11, 'description' => 'Manage Equipment',    'created_at' => NOW(), 'updated_at' => NOW()],
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
