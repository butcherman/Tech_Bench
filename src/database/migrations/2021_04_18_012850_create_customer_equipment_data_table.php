<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerEquipmentDataTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('customer_equipment_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cust_equip_id');
            $table->unsignedBigInteger('field_id');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->foreign('cust_equip_id')
                ->references('cust_equip_id')
                ->on('customer_equipment')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('field_id')
                ->references('field_id')
                ->on('data_fields')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::table('customer_equipment_data', function (Blueprint $table) {
            $table->dropForeign(['cust_equip_id']);
            $table->dropForeign(['field_id']);
        });
        Schema::dropIfExists('customer_equipment_data');
    }
}
