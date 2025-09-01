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
        Schema::create('customer_workbooks', function (Blueprint $table) {
            $table->id('wb_id');
            $table->uuid('wb_hash');
            $table->json('wb_skeleton');
            $table->text('wb_version');
            $table->unsignedBigInteger('cust_equip_id');
            $table->timestamp('publish_until')->nullable();
            $table->timestamps();
            $table->foreign('cust_equip_id')
                ->references('cust_equip_id')
                ->on('customer_equipment')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_workbooks', function (Blueprint $table) {
            $table->dropForeign(['cust_equip_id']);
        });
        Schema::dropIfExists('customer_workbooks');
    }
};
