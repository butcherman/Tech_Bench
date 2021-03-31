<?php

use App\Models\UserEmailNotifications;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEmailNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_email_notifications', function (Blueprint $table) {
            $table->id('user_id')->unsigned();
            $table->boolean('em_tech_tip')->default(1);
            $table->boolean('em_file_link')->default(1);
            $table->boolean('em_notification')->default(1);
            $table->boolean('auto_del_link')->default(1);
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
        UserEmailNotifications::create(['user_id' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_email_notifications', function(Blueprint $table)
        {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('user_email_notifications');
    }
}
