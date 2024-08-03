<?php

use App\Features\TechTipCommentFeature;
use App\Models\UserRolePermissionType;
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

        UserRolePermissionType::where('description', 'Comment on Tech Tip')
            ->update(['feature_name' => TechTipCommentFeature::class]);
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
