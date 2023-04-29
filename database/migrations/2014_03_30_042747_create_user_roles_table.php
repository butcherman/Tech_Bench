<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->text('name');
            $table->text('description');
            $table->boolean('allow_edit')->default(1);
            $table->timestamps();
        });

        //  Insert default data
        $defaultData = [
            ['role_id' => 1, 'name' => 'Installer',     'description' => 'All Access Administrator', 'allow_edit' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['role_id' => 2, 'name' => 'Administrator', 'description' => 'System Administrator',     'allow_edit' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['role_id' => 3, 'name' => 'Reports',       'description' => 'User who can run reports', 'allow_edit' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['role_id' => 4, 'name' => 'Tech',          'description' => 'Standard User',            'allow_edit' => 0, 'created_at' => NOW(), 'updated_at' => NOW()],
        ];
        DB::table('user_roles')->insert($defaultData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
