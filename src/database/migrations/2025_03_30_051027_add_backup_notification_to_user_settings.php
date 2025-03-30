<?php

use App\Services\User\UserSettingsService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permType = DB::table('user_role_permission_types')
            ->where('description', 'App Settings')
            ->first();

        DB::table('user_setting_types')->insert([
            'name' => 'Receive System Backup Notifications',
            'perm_type_id' => $permType->perm_type_id,
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);

        $svc = new UserSettingsService;
        $svc->verifyUserSettings(true);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('user_setting_types')
            ->where('name', 'Receive System Backup Notifications')
            ->delete();
    }
};
