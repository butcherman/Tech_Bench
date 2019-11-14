<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('user_id');
            $table->string('username')->unique;
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('active');
            $table->boolean('is_installer')->default(0);
            $table->timestamp('password_expires')->nullable();
            $table->timestamps();
        });

        //  Create the initial default user
        User::create([
            'user_id'      => 1,
            'username'     => 'admin',
            'first_name'   => 'System',
            'last_name'    => 'Administrator',
            'email'        => 'admin@em.com',
            'password'     => bcrypt('password'),
            'active'       => 1,
           'is_installer' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
