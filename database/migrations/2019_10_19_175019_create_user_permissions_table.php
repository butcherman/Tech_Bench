<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->increments('user_id')->unsigned();
            $table->boolean('manage_users')->default(0);
            $table->boolean('run_reports')->default(0);
            $table->boolean('add_customer')->default(1);
            $table->boolean('deactivate_customer')->default(0);
            $table->boolean('use_file_links')->default(1);
            $table->boolean('create_tech_tip')->default(1);
            $table->boolean('edit_tech_tip')->default(0);
            $table->boolean('delete_tech_tip')->default(0);
            $table->boolean('create_category')->default(0);
            $table->boolean('modify_category')->default(0);
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
