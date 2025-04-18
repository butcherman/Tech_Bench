<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->text('key');
            $table->text('value');
            $table->timestamps();
        });

        /**
         * Default Settings
         */
        $firstTimeInit = [[
            'key' => 'app.first_time_setup',
            'value' => true,
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]];

        DB::table('app_settings')->insert($firstTimeInit);
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
}
