<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentCategoriesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->id('cat_id');
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::dropIfExists('equipment_categories');
    }
}
