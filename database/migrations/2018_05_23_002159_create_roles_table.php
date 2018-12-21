<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function(Blueprint $table) {
            $table->increments('role_id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
        
        //  Input default data
        Role::create([
            'role_id'     => 1,
            'name'        => 'Installer', 
            'description' => 'All Access Administrator'
        ]);
        Role::create([
            'role_id'     => 2,
            'name'        => 'Admin',
            'description' => 'Administrator User'
        ]);
        Role::create([
            'role_id'     => 3,
            'name'        => 'Report',
            'description' => 'User with Reporting Ability'
        ]);
        Role::create([
            'role_id'     => 4,
            'name'        => 'Tech',
            'description' => 'Standard User'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
