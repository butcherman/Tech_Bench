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
        Schema::create('customer_alerts', function (Blueprint $table) {
            $table->id('alert_id');
            $table->unsignedBigInteger('cust_id');
            $table->text('type');
            $table->text('message');
            $table->timestamps();
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
        Schema::table('customer_alerts', function (Blueprint $table) {
            $table->dropForeign(['cust_id']);
        });
        Schema::dropIfExists('customer_alerts');
    }
};
