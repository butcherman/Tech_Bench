<?php

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
        Schema::table('data_field_types', function (Blueprint $table) {
            $table->string('pattern')->after('name')->nullable();
            $table->boolean('required')->after('pattern')->default(false);
            $table->renameColumn('hidden', 'masked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_field_types', function (Blueprint $table) {
            $table->dropColumn('pattern');
            $table->dropColumn('required');
            $table->renameColumn('masked', 'hidden');
        });
    }
};
