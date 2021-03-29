<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerEquipmentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_equipment_data', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('cust_sys_id')->unsigned();
            $table->integer('field_id')->unsigned();
            $table->text('value')->nullable();
            $table->timestamps();
            $table->foreign('cust_sys_id')->references('cust_sys_id')->on('customer_equipment')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('field_id')->references('field_id')->on('equipment_data_fields')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_equipment_data', function(Blueprint $table)
        {
            $table->dropForeign(['cust_sys_id', 'field_id']);
        });
        Schema::dropIfExists('customer_equipment_data');
    }
}
