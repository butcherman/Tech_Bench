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
        Schema::create('customer_equipment_workbooks', function (Blueprint $table) {
            $table->id('wb_id');
            $table->uuid('wb_hash');
            $table->unsignedBigInteger('cust_id');
            $table->unsignedBigInteger('cust_equip_id');
            $table->json('wb_skeleton');
            $table->text('wb_version');
            $table->timestamp('publish_until')->nullable();
            $table->timestamps();
            $table->foreign('cust_equip_id')
                ->references('cust_equip_id')
                ->on('customer_equipment')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('cust_id')
                ->references('cust_id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unique(['cust_id', 'cust_equip_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_equipment_workbooks', function (Blueprint $table) {
            $table->dropForeign(['cust_equip_id']);
            $table->dropForeign(['cust_id']);
        });
        Schema::dropIfExists('customer_equipment_workbooks');
    }
};
