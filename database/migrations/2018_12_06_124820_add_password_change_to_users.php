<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Settings;
use App\User;

class AddPasswordChangeToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->timestamp('password_expires')
                ->nullable()
                ->after('active');
        });
        
        //  Add the epiration timer to the settings database table
        Settings::insert([
            'key'   => 'users.passExpires',
            'value' => '90'
        ]);
        
        //  Update all of the existing users to set the password expiration date
        User::where('password_expires', null)->update([
            'password_expires' => date('Y-m-d', strtotime('+30 days'))
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('password_expires');
        });
        
        Settings::where('key', 'users.passExpires')->delete();
    }
}
