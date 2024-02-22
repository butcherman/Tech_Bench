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
        Schema::create('customer_sites', function (Blueprint $table) {
            $table->id('cust_site_id');
            $table->unsignedBigInteger('cust_id');
            $table->text('site_name');
            $table->text('site_slug');
            $table->text('address');
            $table->text('city');
            $table->text('state');
            $table->integer('zip');
            $table->text('deleted_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('cust_id')
                ->references('cust_id')
                ->on('customers')
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_sites', function (Blueprint $table) {
            $table->dropForeign(['cust_id']);
        });
        Schema::dropIfExists('customer_sites');
    }
};
