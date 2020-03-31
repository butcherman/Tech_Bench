<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRoleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_types', function (Blueprint $table) {
            $table->increments('role_id');
            $table->text('name');
            $table->text('description');
            $table->boolean('allow_edit')->default(1);
            $table->timestamps();
        });

        //  Insert default data
        DB::table('user_role_types')->insert([
            ['role_id' => 1, 'name' => 'Installer',     'description' => 'All Access Administrator',    'allow_edit' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['role_id' => 2, 'name' => 'Administrator', 'description' => 'System Administrator',        'allow_edit' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['role_id' => 3, 'name' => 'Reports',       'description' => 'User who can run reports',    'allow_edit' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['role_id' => 4, 'name' => 'Tech',          'description' => 'Standard User',               'allow_edit' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        //  Update the users table to include the 'user_role' column
        if(!Schema::hasColumn('users', 'role_id'))
        {
            Schema::table('users', function(Blueprint $table)
            {
                $table->integer('role_id')->unsigned()->after('user_id')->default(4);
                $table->foreign('role_id')->references('role_id')->on('user_role_types')->onUpdate('cascade');
            });

            DB::update('UPDATE `users` SET `role_id` = 1 WHERE `user_id` = 1');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_role_descriptions');
    }
}
