<?php

use Carbon\Carbon;
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
        Schema::create('settings', function(Blueprint $table) {
            $table->increments('id');
            $table->text('key');
            $table->text('value');
            $table->timestamps();
        });

        //  TODO - simplify this
        //  Insert default settings
        // DB::table('settings')->insert([
        //     ['updated_at' => Carbon::now(), 'created_at' => \Carbon\Carbon::now(), 'key' => 'app.logo',          'value' => config('app.logo')],
        //     // ['created_at' => \Carbon\Carbon::now(), 'key' => 'app.timezone',      'value' => config('app.timezone')],
        //     ['created_at' => \Carbon\Carbon::now(), 'key' => 'mail.host',         'value' => config('mail.host')],
        //     ['created_at' => \Carbon\Carbon::now(), 'key' => 'mail.port',         'value' => config('mail.port')],
        //     ['created_at' => \Carbon\Carbon::now(), 'key' => 'mail.encryption',   'value' => config('mail.encryption')],
        //     ['created_at' => \Carbon\Carbon::now(), 'key' => 'mail.username',     'value' => config('mail.username')],
        //     ['created_at' => \Carbon\Carbon::now(), 'key' => 'mail.password',     'value' => config('mail.password')],
        //     ['created_at' => \Carbon\Carbon::now(), 'key' => 'mail.from.address', 'value' => config('mail.from.address')],
        //     ['created_at' => \Carbon\Carbon::now(), 'key' => 'mail.from.name',    'value' => config('mail.from.name')],
        //     ['created_at' => \Carbon\Carbon::now(), 'key' => 'user.passExpires',  'value' => 30],

        // ]);

        // $defaultSettings = [
        //     'user.passExpires' => 30,
        // ];
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
