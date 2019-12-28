<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Create the trigger to build this table as a new user is created
        DB::unprepared(
            'CREATE TRIGGER `tr_user_settings` AFTER INSERT ON `users`
                FOR EACH ROW
                BEGIN
                    INSERT INTO `user_settings` (`user_id`, `created_at`, `updated_at`) VALUES (NEW.user_id, NOW(), NOW());
                END;'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `tr_user_settings`');
    }
}
