<?php

use App\Models\AppSettings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $allSettings = AppSettings::get();

        Schema::table('app_settings', function (Blueprint $table) {
            $table->renameColumn('value', 'value_old');
            $table->json('value')->after('key')->nullable();
        });

        foreach ($allSettings as $setting) {

            if ($setting->value === '1' || $setting->value === '0') {
                $setting->value = json_encode((bool) $setting->value);
            } else {

                $setting->value = json_encode($setting->value);
            }
            $setting->save();
        }

        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn('value_old');
        });
    }
};
