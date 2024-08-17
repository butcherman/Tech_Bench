<?php

use App\Features\PublicTechTipFeature;
use App\Features\TechTipCommentFeature;
use App\Models\UserRolePermissionType;
use App\Models\UserSettingType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_role_permission_types', function (Blueprint $table) {
            $table->string('feature_name')->after('is_admin_link')->nullable();
        });

        Schema::table('user_role_permission_categories', function (Blueprint $table) {
            $table->string('feature_name')->after('category')->nullable();
        });

        Schema::table('user_setting_types', function (Blueprint $table) {
            $table->string('feature_name')->after('perm_type_id')->nullable();
            $table->string('config_key')->after('feature_name')->nullable();
        });

        UserRolePermissionType::where('description', 'Comment on Tech Tip')
            ->update(['feature_name' => TechTipCommentFeature::class]);
        UserRolePermissionType::where('description', 'Add Public Tech Tip')
            ->update(['feature_name' => PublicTechTipFeature::class]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_role_permission_types', function (Blueprint $table) {
            $table->removeColumn('feature_name');
        });
    }
};
