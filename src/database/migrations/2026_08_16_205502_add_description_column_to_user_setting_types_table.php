<?php

use App\Models\UserSettingType;
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
        Schema::table('user_setting_types', function (Blueprint $table) {
            $table->text('description')->after('name');
        });

        /**
         * Add Description to the existing entries
         */
        $emNotif = UserSettingType::where('name', 'Receive Email Notifications')
            ->first();
        $emNotif->description = 'Receive email notifications from '.config('app.name');
        $emNotif->save();

        $fileLinks = UserSettingType::where('name', 'Auto Delete Expired File Links')
            ->first();
        $fileLinks->description =
            'Auto delete file links and attached files after they have been expired for a set amount of time';
        $fileLinks->save();

        $backupNotif = UserSettingType::where('name', 'Receive System Backup Notifications')
            ->first();
        $backupNotif->description = 'Receive an email on success or failure of a system backup';
        $backupNotif->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_setting_types', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
