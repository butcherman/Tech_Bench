<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('key');
            $table->text('value');
            $table->timestamps();
        });
        
        //  Insert default settings
        DB::table('settings')->insert([
            ['key' => 'app.logo', 'value' => config('app.logo')],
            ['key' => 'app.url', 'value' => config('app.url')],
            ['key' => 'app.timezone', 'value' => config('app.timezone')],
            ['key' => 'mail.driver', 'value' => config('mail.driver')],
            ['key' => 'mail.host', 'value' => config('mail.host')],
            ['key' => 'mail.port', 'value' => config('mail.port')],
            ['key' => 'mail.encryption', 'value' => config('mail.encryption')],
            ['key' => 'mail.username', 'value' => config('mail.username')],
            ['key' => 'mail.password', 'value' => config('mail.password')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
