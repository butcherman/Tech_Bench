<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_setting_types', function (Blueprint $table) {
            $table->id('setting_type_id');
            $table->text('name');
            $table->unsignedBigInteger('perm_type_id')->nullable();
            $table->timestamps();
        });

        $default = [
            ['setting_type_id' => 1, 'name' => 'Receive Email Notifications',               'updated_at' => NOW(), 'created_at' => NOW()],
            ['setting_type_id' => 2, 'name' => 'Receive Bookmarked Customer Notifications', 'updated_at' => NOW(), 'created_at' => NOW()],
        ];

        DB::table('user_setting_types')->insert($default);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_setting_types');
    }
}
