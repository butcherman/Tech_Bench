<?php

use App\UserSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->boolean('em_tech_tip')->default(1);
            $table->boolean('em_file_link')->default(1);
            $table->boolean('em_notification')->default(1);
            $table->boolean('auto_del_link')->default(1);
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
        // UserSettings::create([
        //     'user_id' => 1,
        // ]);
        DB::table('user_settings')->insert([
            'user_id' => 1,
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);
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
        });
        Schema::dropIfExists('user_settings');
    }
}
