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
        Schema::table('file_link_files', function (Blueprint $table) {
            $table->boolean('moved')->default(false)->after('upload');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_link_files', function (Blueprint $table) {
            $table->dropColumn('moved');
        });
    }
};
