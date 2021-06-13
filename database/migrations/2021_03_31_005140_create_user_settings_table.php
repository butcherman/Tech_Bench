<?php

use App\Models\UserEmailNotifications;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id('setting_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('setting_type_id');
            $table->boolean('value')->default(1);
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('setting_type_id')->references('setting_type_id')->on('user_setting_types')->onUpdate('cascade')->onDelete('cascade');
        });

        //  Add the default admin user to the table
        $default = [
            ['user_id' => 1, 'setting_type_id' => 1, 'value' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['user_id' => 1, 'setting_type_id' => 2, 'value' => 1, 'created_at' => NOW(), 'updated_at' => NOW()],
        ];

        DB::table('user_settings')->insert($default);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_settings', function(Blueprint $table)
        {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['setting_type_id']);
        });
        Schema::dropIfExists('user_settings');
    }
}
