<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTypesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('equipment_types', function (Blueprint $table) {
            $table->id('equip_id');
            $table->unsignedBigInteger('cat_id');
            $table->string('name')->unique();
            $table->timestamps();
            $table->foreign('cat_id')
                ->references('cat_id')
                ->on('equipment_categories')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('equipment_types', function (Blueprint $table) {
            $table->dropForeign(['cat_id']);
        });
        Schema::dropIfExists('equipment_types');
    }
}
