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
        Schema::table('file_links', function (Blueprint $table) {
            $table->unsignedBigInteger('cust_id')
                ->after('allow_upload')
                ->nullable();
            $table->foreign('cust_id')
                ->references('cust_id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_links', function (Blueprint $table) {
            $table->dropForeign(['cust_id']);
            $table->dropColumn('cust_id');
        });
    }
};
