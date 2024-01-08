<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_equipment', function (Blueprint $table) {
            $table->id('cust_equip_id');
            $table->unsignedBigInteger('cust_id');
            $table->unsignedBigInteger('equip_id');
            $table->boolean('shared');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('cust_id')->references('cust_id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('equip_id')->references('equip_id')->on('equipment_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_equipment', function (Blueprint $table) {
            $table->dropForeign(['cust_id', 'equip_id']);
        });
        Schema::dropIfExists('customer_equipment');
    }
}
