<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data_field_types', function (Blueprint $table) {
            $table->string('pattern')->after('name')->nullable();
            $table->boolean('is_hyperlink')->after('pattern')->default(false);
            $table->boolean('allow_copy')->after('is_hyperlink')->default(false);
            $table->renameColumn('hidden', 'masked');
        });

        /**
         * Fix the Foreign Key restraint on the data_fields table to not cascade on delete
         */
        Schema::table('data_fields', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->foreign('type_id')
                ->references('type_id')
                ->on('data_field_types')
                ->onUpdate('cascade');
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
