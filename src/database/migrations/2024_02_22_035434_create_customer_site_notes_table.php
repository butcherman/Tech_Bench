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
        Schema::create('customer_site_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('note_id');
            $table->unsignedBigInteger('cust_site_id');
            $table->foreign('note_id')
                ->references('note_id')
                ->on('customer_notes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('cust_site_id')
                ->references('cust_site_id')
                ->on('customer_sites')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_site_notes', function (Blueprint $table) {
            $table->dropForeign(['cust_site_id']);
            $table->dropForeign(['note_id']);
        });
        Schema::dropIfExists('customer_site_notes');
    }
};
